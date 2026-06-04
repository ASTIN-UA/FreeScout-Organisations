<?php

namespace Modules\OrgPortal\Http\Controllers;

use App\Conversation;
use App\Customer;
use App\Mailbox;
use App\Thread;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\OrgPortal\Models\Organization;
use Modules\OrgPortal\Models\OrganizationMember;

class OrgPortalFrontController extends Controller
{
    // ─── Auth / helpers ───────────────────────────────────────────────────────

    protected function authCustomer(): Customer
    {
        $customer = \EndUserPortal::authCustomer();

        if (!$customer) {
            throw new HttpResponseException(
                redirect()->route('enduserportal.login', [
                    'mailbox_id' => request()->route('mailbox_id'),
                ])
            );
        }

        return $customer;
    }

    protected function getMailbox(string $encodedId): Mailbox
    {
        $id      = \EndUserPortal::decodeMailboxId($encodedId);
        $mailbox = $id ? Mailbox::find($id) : null;

        if (!$mailbox) {
            abort(404);
        }

        return $mailbox;
    }

    protected function requireManager(Customer $customer): OrganizationMember
    {
        $member = OrganizationMember::where('customer_id', $customer->id)
            ->where('role', 'manager')
            ->with('organization')
            ->first();

        if (!$member) {
            abort(403, __('orgportal::messages.access_denied'));
        }

        return $member;
    }

    // ─── Company Tickets ─────────────────────────────────────────────────────

    public function companyTickets(Request $request, string $mailbox_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();
        $member   = $this->requireManager($customer);

        $orgMemberIds = OrganizationMember::where('organization_id', $member->organization_id)
            ->pluck('customer_id');

        $conversations = Conversation::whereIn('customer_id', $orgMemberIds)
            ->where('status', '!=', Conversation::STATUS_SPAM)
            ->where('state', '!=', Conversation::STATE_DELETED)
            ->orderBy('updated_at', 'desc')
            ->with('customer')
            ->paginate(25);

        return view('orgportal::portal.company_tickets', [
            'mailbox'       => $mailbox,
            'mailbox_id'    => $mailbox_id,
            'customer'      => $customer,
            'organization'  => $member->organization,
            'conversations' => $conversations,
        ]);
    }

    // ─── View single ticket ──────────────────────────────────────────────────

    public function viewTicket(Request $request, string $mailbox_id, int $conversation_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();
        $member   = $this->requireManager($customer);

        $orgMemberIds = OrganizationMember::where('organization_id', $member->organization_id)
            ->pluck('customer_id');

        $conversation = Conversation::whereIn('customer_id', $orgMemberIds)
            ->findOrFail($conversation_id);

        $threads = $conversation->threads()
            ->whereIn('type', [Thread::TYPE_CUSTOMER, Thread::TYPE_MESSAGE])
            ->where('state', Thread::STATE_PUBLISHED)
            ->orderBy('created_at')
            ->get();

        // Mark agent threads as opened
        foreach ($threads as $thread) {
            if ($thread->type === Thread::TYPE_MESSAGE && !$thread->opened_at) {
                $thread->opened_at = now();
                $thread->save();
            }
        }

        return view('orgportal::portal.ticket', [
            'mailbox'       => $mailbox,
            'mailbox_id'    => $mailbox_id,
            'customer'      => $customer,
            'conversation'  => $conversation,
            'threads'       => $threads,
        ]);
    }

    // ─── Reply ───────────────────────────────────────────────────────────────

    public function replyTicket(Request $request, string $mailbox_id, int $conversation_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();
        $member   = $this->requireManager($customer);

        $orgMemberIds = OrganizationMember::where('organization_id', $member->organization_id)
            ->pluck('customer_id');

        $conversation = Conversation::whereIn('customer_id', $orgMemberIds)
            ->findOrFail($conversation_id);

        $request->validate([
            'body' => 'required|string|min:1|max:65000',
        ]);

        $body = nl2br(htmlspecialchars($request->input('body')));

        $thread              = new Thread();
        $thread->conversation_id     = $conversation->id;
        $thread->user_id             = null;
        $thread->type                = Thread::TYPE_CUSTOMER;
        $thread->status              = Thread::STATUS_ACTIVE;
        $thread->state               = Thread::STATE_PUBLISHED;
        $thread->body                = $body;
        $thread->source_via          = Thread::PERSON_CUSTOMER;
        $thread->source_type         = Thread::SOURCE_TYPE_WEB;
        $thread->customer_id         = $customer->id;
        $thread->created_by_customer_id = $customer->id;
        $thread->from                = $customer->getMainEmail();
        $thread->save();

        $conversation->status          = Conversation::STATUS_ACTIVE;
        $conversation->last_reply_at   = now();
        $conversation->last_reply_from = Conversation::PERSON_CUSTOMER;
        $conversation->save();

        $conversation = \Eventy::filter('conversation.customer_replied', $conversation, $thread, $customer);
        $conversation->save();

        // Fire the Laravel event so FreeScout's native agent notifications and
        // third-party modules (Workflows, Mobile Notifications) are triggered.
        event(new \App\Events\CustomerReplied($conversation, $thread));
        \Eventy::action('conversation.customer_replied', $conversation, $thread, $customer);

        return redirect()
            ->route('orgportal.portal.ticket', ['mailbox_id' => $mailbox_id, 'conversation_id' => $conversation_id])
            ->with('flash_success', __('orgportal::messages.reply_sent'));
    }

    // ─── Settings ────────────────────────────────────────────────────────────

    public function settings(Request $request, string $mailbox_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();

        $member = OrganizationMember::where('customer_id', $customer->id)
            ->where('role', 'manager')
            ->first();

        if (!$member) {
            abort(403, __('orgportal::messages.access_denied'));
        }

        return view('orgportal::portal.settings', [
            'mailbox'    => $mailbox,
            'mailbox_id' => $mailbox_id,
            'customer'   => $customer,
            'member'     => $member,
        ]);
    }

    public function saveSettings(Request $request, string $mailbox_id)
    {
        $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();

        $member = OrganizationMember::where('customer_id', $customer->id)
            ->where('role', 'manager')
            ->first();

        if (!$member) {
            abort(403, __('orgportal::messages.access_denied'));
        }

        $member->notify_on_new_ticket = (bool) $request->input('notify_on_new_ticket', false);
        $member->save();

        return redirect()
            ->route('orgportal.portal.settings', ['mailbox_id' => $mailbox_id])
            ->with('flash_success', __('orgportal::messages.settings_saved'));
    }
}

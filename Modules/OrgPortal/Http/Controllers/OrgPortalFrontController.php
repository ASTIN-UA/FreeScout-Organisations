<?php

namespace Modules\OrgPortal\Http\Controllers;

use App\Attachment;
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

    protected function requireManager(Customer $customer, Mailbox $mailbox): OrganizationMember
    {
        $member = OrganizationMember::where('customer_id', $customer->id)
            ->where('role', 'manager')
            ->with('organization')
            ->first();

        if (!$member || !$member->organization) {
            abort(403, __('orgportal::messages.access_denied'));
        }

        // Enforce mailbox scope: org must be global (mailbox_id IS NULL) or belong to this mailbox.
        $orgMailboxId = $member->organization->mailbox_id;
        if ($orgMailboxId !== null && $orgMailboxId !== (int) $mailbox->id) {
            abort(403, __('orgportal::messages.access_denied'));
        }

        return $member;
    }

    // ─── Company Tickets ─────────────────────────────────────────────────────

    public function companyTickets(Request $request, string $mailbox_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();
        $member   = $this->requireManager($customer, $mailbox);

        $orgMemberIds   = OrganizationMember::where('organization_id', $member->organization_id)
            ->pluck('customer_id');

        $orderField     = 'last_reply_at';
        $orderDirection = $request->input('order', 'desc');
        $searchField    = $request->input('searchField', '');
        $status         = $request->input('status', []);
        $closed         = (bool) $request->input('closed', false);
        $direction      = $orderDirection === 'asc' ? 'desc' : 'asc';

        $authorId   = (int) $request->input('author_id', 0) ?: null;
        $authorName = null;
        if ($authorId) {
            $author     = Customer::find($authorId);
            $authorName = $author ? trim($author->getFullName()) : null;
            if (!$authorName || !$orgMemberIds->contains($authorId)) {
                $authorId = null; // ignore invalid / out-of-org author filter
            }
        }

        $builder = Conversation::whereIn('customer_id', $orgMemberIds)
            ->where('mailbox_id', $mailbox->id)
            ->where('state', '!=', Conversation::STATE_DELETED)
            ->with(['customer', 'user'])
            ->when($searchField, fn ($q) => $q->where('subject', 'like', "%{$searchField}%"))
            ->when($authorId,    fn ($q) => $q->where('customer_id', $authorId))
            ->when($closed,      fn ($q) => $q->where('status', Conversation::STATUS_CLOSED),
                                 fn ($q) => $q->where('status', '!=', Conversation::STATUS_SPAM))
            ->orderBy($orderField, $orderDirection);

        if (!empty($status) && is_array($status)) {
            if (\Module::isActive('kanban')) {
                $convIds = \Modules\Kanban\Entities\KnCard::whereIn('kn_column_id', array_values($status))
                    ->pluck('conversation_id');
                $builder->whereIn('id', $convIds);
            } else {
                // Kanban is inactive — status filters reference column IDs that don't exist.
                // Return nothing rather than silently ignoring the filter.
                $builder->whereRaw('0 = 1');
            }
        }

        $tickets = $builder->paginate(20);

        // Set has_new_replies (unread agent replies) — same logic as EUP
        $convIds = $tickets->pluck('id');
        $latestThreads = Thread::whereIn('conversation_id', $convIds)
            ->whereIn('type', [Thread::TYPE_CUSTOMER, Thread::TYPE_MESSAGE])
            ->where('state', Thread::STATE_PUBLISHED)
            ->orderByDesc('id')
            ->get(['id', 'conversation_id', 'type', 'opened_at']);

        foreach ($tickets as $ticket) {
            $ticket->has_new_replies = false;
            foreach ($latestThreads as $thread) {
                if ($ticket->id === $thread->conversation_id) {
                    if ($thread->type === Thread::TYPE_MESSAGE && !$thread->opened_at) {
                        $ticket->has_new_replies = true;
                    }
                    break;
                }
            }
        }

        $tickets->appends(array_filter([
            'order'       => $request->input('order'),
            'searchField' => $searchField  ?: null,
            'status'      => $status       ?: null,
            'author_id'   => $authorId     ?: null,
            'closed'      => $closed       ?: null,
        ]));

        return view('orgportal::portal.company_tickets', [
            'mailbox'      => $mailbox,
            'mailbox_id'   => $mailbox_id,
            'customer'     => $customer,
            'organization' => $member->organization,
            'tickets'      => $tickets,
            'sortField'    => $orderField,
            'direction'    => $direction,
            'searchField'  => $searchField,
            'status'       => $status,
            'closed'       => $closed,
            'authorId'     => $authorId,
            'authorName'   => $authorName,
        ]);
    }

    // ─── View single ticket ──────────────────────────────────────────────────

    public function viewTicket(Request $request, string $mailbox_id, int $conversation_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();
        $member   = $this->requireManager($customer, $mailbox);

        $orgMemberIds = OrganizationMember::where('organization_id', $member->organization_id)
            ->pluck('customer_id');

        $conversation = Conversation::whereIn('customer_id', $orgMemberIds)
            ->where('mailbox_id', $mailbox->id)
            ->findOrFail($conversation_id);

        $threads = $conversation->threads()
            ->whereIn('type', [Thread::TYPE_CUSTOMER, Thread::TYPE_MESSAGE])
            ->where('state', Thread::STATE_PUBLISHED)
            ->orderBy('created_at')
            ->with('attachments')
            ->get();

        // Mark agent threads as opened
        foreach ($threads as $thread) {
            if ($thread->type === Thread::TYPE_MESSAGE && !$thread->opened_at) {
                $thread->opened_at = now();
                $thread->save();
            }
        }

        $orgMembers = Customer::whereIn('id', $orgMemberIds)->get();

        return view('orgportal::portal.ticket', [
            'mailbox'       => $mailbox,
            'mailbox_id'    => $mailbox_id,
            'customer'      => $customer,
            'conversation'  => $conversation,
            'threads'       => $threads,
            'orgMembers'    => $orgMembers,
        ]);
    }

    // ─── Reply ───────────────────────────────────────────────────────────────

    public function replyTicket(Request $request, string $mailbox_id, int $conversation_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();
        $member   = $this->requireManager($customer, $mailbox);

        $orgMemberIds = OrganizationMember::where('organization_id', $member->organization_id)
            ->pluck('customer_id');

        $conversation = Conversation::whereIn('customer_id', $orgMemberIds)
            ->where('mailbox_id', $mailbox->id)
            ->findOrFail($conversation_id);

        $request->validate([
            'message' => 'required|string|min:1|max:65000',
        ]);

        $body = $request->input('message');

        $thread                          = new Thread();
        $thread->conversation_id         = $conversation->id;
        $thread->user_id                 = null;
        $thread->type                    = Thread::TYPE_CUSTOMER;
        $thread->status                  = Thread::STATUS_ACTIVE;
        $thread->state                   = Thread::STATE_PUBLISHED;
        $thread->body                    = $body;
        $thread->source_via              = Thread::PERSON_CUSTOMER;
        $thread->source_type             = Thread::SOURCE_TYPE_WEB;
        $thread->customer_id             = $customer->id;
        $thread->created_by_customer_id  = $customer->id;
        $thread->from                    = $customer->getMainEmail();
        $thread->save();

        // Process EUP-style pre-uploaded attachments (encrypted IDs)
        $attachmentIds = $this->decodeAttachmentIds($request->input('attachments', []));
        $allIds        = $this->decodeAttachmentIds($request->input('attachments_all', []));
        $removeIds     = array_diff($allIds, $attachmentIds);
        if ($removeIds) {
            Attachment::deleteByIds($removeIds);
        }
        if ($attachmentIds) {
            Attachment::whereIn('id', $attachmentIds)
                ->whereNull('thread_id')
                ->update(['thread_id' => $thread->id]);
            $thread->has_attachments       = true;
            $thread->save();
            $conversation->has_attachments = true;
        }

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

    // ─── Change author ───────────────────────────────────────────────────────

    public function changeAuthor(Request $request, string $mailbox_id, int $conversation_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();
        $member   = $this->requireManager($customer, $mailbox);

        $orgMemberIds = OrganizationMember::where('organization_id', $member->organization_id)
            ->pluck('customer_id');

        $conversation = Conversation::whereIn('customer_id', $orgMemberIds)
            ->where('mailbox_id', $mailbox->id)
            ->findOrFail($conversation_id);

        $request->validate([
            'new_customer_id' => 'required|integer',
        ]);

        $newCustomerId = (int) $request->input('new_customer_id');

        if (!$orgMemberIds->contains($newCustomerId)) {
            abort(422, __('orgportal::messages.access_denied'));
        }

        if ($conversation->customer_id === $newCustomerId) {
            return redirect()
                ->route('orgportal.portal.ticket', ['mailbox_id' => $mailbox_id, 'conversation_id' => $conversation_id]);
        }

        $conversation->customer_id = $newCustomerId;
        $conversation->save();

        return redirect()
            ->route('orgportal.portal.ticket', ['mailbox_id' => $mailbox_id, 'conversation_id' => $conversation_id])
            ->with('flash_success', __('orgportal::messages.author_changed'));
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function decodeAttachmentIds(array $list): array
    {
        $ids = [];
        foreach ($list as $encrypted) {
            $id = \Helper::decrypt($encrypted);
            if ($id !== $encrypted) {
                $ids[] = (int) $id;
            }
        }
        return $ids;
    }

    // ─── Close ticket ────────────────────────────────────────────────────────

    public function closeTicket(Request $request, string $mailbox_id, int $conversation_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();
        $member   = $this->requireManager($customer, $mailbox);

        $orgMemberIds = OrganizationMember::where('organization_id', $member->organization_id)
            ->pluck('customer_id');

        $conversation = Conversation::whereIn('customer_id', $orgMemberIds)
            ->where('mailbox_id', $mailbox->id)
            ->findOrFail($conversation_id);

        $prevStatus = $conversation->status;
        $conversation->setStatus(Conversation::STATUS_CLOSED);
        $conversation->closed_at = now();
        $conversation->save();

        event(new \App\Events\ConversationStatusChanged($conversation));
        \Eventy::action('conversation.status_changed', $conversation, null, false, $prevStatus);

        return redirect()
            ->route('orgportal.portal.company-tickets', ['mailbox_id' => $mailbox_id])
            ->with('flash_success', __('orgportal::messages.ticket_closed'));
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

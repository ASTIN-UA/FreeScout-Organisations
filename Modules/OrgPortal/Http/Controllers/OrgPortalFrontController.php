<?php

namespace Modules\OrgPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customer;
use App\Conversation;
use App\Thread;
use Modules\OrgPortal\Models\Organization;
use Modules\OrgPortal\Models\OrganizationMember;

class OrgPortalFrontController extends Controller
{
    // ─── Auth helper ─────────────────────────────────────────────────────────

    /**
     * Returns the logged-in EUP customer or aborts with redirect.
     */
    protected function getEupCustomer(): Customer
    {
        $customerId = \Session::get('eup_customer_id');

        if (!$customerId) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                redirect()->route('eup.login')
            );
        }

        $customer = Customer::find($customerId);

        if (!$customer) {
            \Session::forget('eup_customer_id');
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                redirect()->route('eup.login')
            );
        }

        return $customer;
    }

    /**
     * Returns the OrganizationMember record for this customer or aborts 403.
     */
    protected function getManagerMember(Customer $customer): OrganizationMember
    {
        $member = OrganizationMember::where('customer_id', $customer->id)
            ->where('role', 'manager')
            ->first();

        if (!$member) {
            abort(403, __('Access denied. Manager role required.'));
        }

        return $member;
    }

    // ─── Company Tickets ─────────────────────────────────────────────────────

    public function companyTickets(Request $request)
    {
        $customer = $this->getEupCustomer();
        $member   = $this->getManagerMember($customer);

        // Collect all customer IDs in the organization
        $orgMemberIds = OrganizationMember::where('organization_id', $member->organization_id)
            ->pluck('customer_id');

        $conversations = Conversation::whereIn('customer_id', $orgMemberIds)
            ->whereNotIn('status', [\App\Conversation::STATUS_SPAM])
            ->orderBy('updated_at', 'desc')
            ->paginate(25);

        // Eager-load customers for display
        $conversations->load('customer');

        return view('orgportal::portal.company_tickets', [
            'customer'      => $customer,
            'organization'  => $member->organization,
            'conversations' => $conversations,
        ]);
    }

    // ─── View single ticket ──────────────────────────────────────────────────

    public function viewTicket(Request $request, int $id)
    {
        $customer = $this->getEupCustomer();
        $member   = $this->getManagerMember($customer);

        $orgMemberIds = OrganizationMember::where('organization_id', $member->organization_id)
            ->pluck('customer_id');

        $conversation = Conversation::whereIn('customer_id', $orgMemberIds)
            ->findOrFail($id);

        $threads = $conversation->threads()
            ->whereIn('type', [\App\Thread::TYPE_CUSTOMER, \App\Thread::TYPE_MESSAGE])
            ->orderBy('created_at')
            ->get();

        return view('orgportal::portal.ticket', [
            'customer'     => $customer,
            'conversation' => $conversation,
            'threads'      => $threads,
        ]);
    }

    // ─── Reply to ticket ─────────────────────────────────────────────────────

    public function replyTicket(Request $request, int $id)
    {
        $customer = $this->getEupCustomer();
        $member   = $this->getManagerMember($customer);

        $orgMemberIds = OrganizationMember::where('organization_id', $member->organization_id)
            ->pluck('customer_id');

        $conversation = Conversation::whereIn('customer_id', $orgMemberIds)
            ->findOrFail($id);

        $request->validate([
            'body' => 'required|string|min:1|max:65000',
        ]);

        // Create a customer thread from the manager's own account
        $thread = Thread::create([
            'conversation_id' => $conversation->id,
            'user_id'         => null,
            'type'            => Thread::TYPE_CUSTOMER,
            'body'            => clean($request->input('body')),
            'status'          => Thread::STATUS_ACTIVE,
            'state'           => Thread::STATE_PUBLISHED,
            'customer_id'     => $customer->id,
            'source_via'      => Thread::PERSON_CUSTOMER,
            'source_type'     => Thread::SOURCE_TYPE_WEB,
        ]);

        // Update conversation status back to active if it was closed/pending
        if ($conversation->status !== Conversation::STATUS_ACTIVE) {
            $conversation->status = Conversation::STATUS_ACTIVE;
            $conversation->save();
        }

        \Eventy::action('conversation.customer_replied', $conversation, $thread, $customer);

        return redirect()->route('orgportal.portal.ticket', $id)
            ->with('flash_success', __('Reply sent.'));
    }

    // ─── Settings ────────────────────────────────────────────────────────────

    public function settings(Request $request)
    {
        $customer = $this->getEupCustomer();

        $member = OrganizationMember::where('customer_id', $customer->id)
            ->where('role', 'manager')
            ->first();

        if (!$member) {
            abort(403, __('Access denied. Manager role required.'));
        }

        return view('orgportal::portal.settings', [
            'customer' => $customer,
            'member'   => $member,
        ]);
    }

    public function saveSettings(Request $request)
    {
        $customer = $this->getEupCustomer();

        $member = OrganizationMember::where('customer_id', $customer->id)
            ->where('role', 'manager')
            ->firstOrFail();

        $member->notify_on_new_ticket = (bool) $request->input('notify_on_new_ticket', false);
        $member->save();

        return redirect()->route('orgportal.portal.settings')
            ->with('flash_success', __('Settings saved.'));
    }
}

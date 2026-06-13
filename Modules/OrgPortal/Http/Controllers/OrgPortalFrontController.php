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
use Modules\OrgPortal\Models\OrganizationUnit;

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

    /**
     * Customer IDs whose tickets this manager may see.
     *
     * Global manager (unit_id IS NULL) → every member of the organization.
     * Unit manager → members of that unit only.
     *
     * Includes inactive (deactivated) members so a fired person's existing
     * tickets stay visible. Assignment is restricted separately, see
     * {@see assignableCustomerIds()}.
     */
    protected function visibleCustomerIds(OrganizationMember $member)
    {
        $query = OrganizationMember::where('organization_id', $member->organization_id);

        if ($member->isUnitManager()) {
            $query->where('unit_id', $member->unit_id);
        }

        return $query->pluck('customer_id');
    }

    /**
     * Customer IDs this manager may assign as a ticket author — active members
     * only (deactivated members cannot receive new ticket assignments).
     */
    protected function assignableCustomerIds(OrganizationMember $member)
    {
        $query = OrganizationMember::where('organization_id', $member->organization_id)
            ->where('is_active', true);

        if ($member->isUnitManager()) {
            $query->where('unit_id', $member->unit_id);
        }

        return $query->pluck('customer_id');
    }

    // ─── Company Tickets ─────────────────────────────────────────────────────

    public function companyTickets(Request $request, string $mailbox_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();
        $member   = $this->requireManager($customer, $mailbox);

        $org          = $member->organization;
        $orgMemberIds = $this->visibleCustomerIds($member);

        $orderField     = 'last_reply_at';
        $orderDirection = $request->input('order', 'desc');
        $searchField    = $request->input('searchField', '');
        $status         = $request->input('status', []);
        $closed         = (bool) $request->input('closed', false);
        $direction      = $orderDirection === 'asc' ? 'desc' : 'asc';

        // Unit filter — only global managers can filter by unit
        $unitId = null;
        $units  = collect();
        if ($member->isGlobalManager()) {
            $units  = $org->units()->orderBy('name')->get();
            $unitId = (int) $request->input('unit_id', 0) ?: null;
            if ($unitId && !$units->contains('id', $unitId)) {
                $unitId = null;
            }
        }

        // If filtering by unit, narrow member IDs to that unit only
        $filteredMemberIds = $orgMemberIds;
        if ($unitId) {
            $filteredMemberIds = OrganizationMember::where('organization_id', $org->id)
                ->where('unit_id', $unitId)
                ->pluck('customer_id');
        }

        $authorId   = (int) $request->input('author_id', 0) ?: null;
        $authorName = null;
        if ($authorId) {
            $author     = Customer::find($authorId);
            $authorName = $author ? trim($author->getFullName()) : null;
            if (!$authorName || !$orgMemberIds->contains($authorId)) {
                $authorId = null;
            }
        }

        $builder = Conversation::whereIn('customer_id', $filteredMemberIds)
            ->where('mailbox_id', $mailbox->id)
            ->where('state', '!=', Conversation::STATE_DELETED)
            ->with(['customer', 'user'])
            ->when($searchField, function ($q) use ($searchField, $orgMemberIds) {
                // Search by subject, ticket number, or author name
                $num = preg_replace('/\D/', '', $searchField);
                $matchingCustomerIds = Customer::whereIn('id', $orgMemberIds)
                    ->where(function ($cq) use ($searchField) {
                        $cq->where(\DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$searchField}%")
                           ->orWhere('first_name', 'like', "%{$searchField}%")
                           ->orWhere('last_name',  'like', "%{$searchField}%");
                    })->pluck('id');
                $q->where(function ($sq) use ($searchField, $num, $matchingCustomerIds) {
                    $sq->where('subject', 'like', "%{$searchField}%");
                    if ($num !== '') {
                        $sq->orWhere('number', (int) $num);
                    }
                    if ($matchingCustomerIds->isNotEmpty()) {
                        $sq->orWhereIn('customer_id', $matchingCustomerIds);
                    }
                });
            })
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
            'unit_id'     => $unitId       ?: null,
        ]));

        return view('orgportal::portal.company_tickets', [
            'mailbox'      => $mailbox,
            'mailbox_id'   => $mailbox_id,
            'customer'     => $customer,
            'organization' => $org,
            'tickets'      => $tickets,
            'sortField'    => $orderField,
            'direction'    => $direction,
            'searchField'  => $searchField,
            'status'       => $status,
            'closed'       => $closed,
            'authorId'     => $authorId,
            'authorName'   => $authorName,
            'units'        => $units,
            'unitId'       => $unitId,
        ]);
    }

    // ─── View single ticket ──────────────────────────────────────────────────

    public function viewTicket(Request $request, string $mailbox_id, int $conversation_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();
        $member   = $this->requireManager($customer, $mailbox);

        $orgMemberIds = $this->visibleCustomerIds($member);

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

        // Author dropdown — only members who can still be assigned (active).
        $orgMembers = Customer::whereIn('id', $this->assignableCustomerIds($member))->get();

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

        $orgMemberIds = $this->visibleCustomerIds($member);

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

        $orgMemberIds = $this->visibleCustomerIds($member);

        $conversation = Conversation::whereIn('customer_id', $orgMemberIds)
            ->where('mailbox_id', $mailbox->id)
            ->findOrFail($conversation_id);

        $request->validate([
            'new_customer_id' => 'required|integer',
        ]);

        $newCustomerId = (int) $request->input('new_customer_id');

        // New author must be an active, assignable member in the manager's scope.
        if (!$this->assignableCustomerIds($member)->contains($newCustomerId)) {
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

        $orgMemberIds = $this->visibleCustomerIds($member);

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
        $member   = $this->requireManager($customer, $mailbox);

        $org   = $member->organization;
        $units = $org->units()
            ->with(['members' => function ($q) {
                $q->with('customer')->where('is_active', true)->orderBy('id');
            }])
            ->orderBy('name')
            ->get();

        // Global manager sees every member; a unit manager only their unit.
        $membersQuery = OrganizationMember::where('organization_id', $org->id)
            ->with(['customer', 'unit']);
        if ($member->isUnitManager()) {
            $membersQuery->where('unit_id', $member->unit_id);
        }
        $members = $membersQuery->get()
            ->sortBy(fn ($m) => mb_strtolower(optional($m->customer)->getFullName() ?: ''))
            ->values();

        // Build subscription lookup for current manager: "event:scope_type:scope_id" => true
        $rawSubs = \Modules\OrgPortal\Models\OrgNotificationSubscription::where('member_id', $member->id)->get();
        $subsMap = [];
        foreach ($rawSubs as $s) {
            $key = $s->event . ':' . $s->scope_type . ':' . ($s->scope_id ?? '');
            $subsMap[$key] = true;
        }

        // Build per-member subscription map for members in manager's scope (excluding self).
        $scopedMemberIds = $members->where('id', '!=', $member->id)->pluck('id')->toArray();
        $memberSubsMap   = [];
        if (!empty($scopedMemberIds)) {
            $rawMemberSubs = \Modules\OrgPortal\Models\OrgNotificationSubscription::whereIn('member_id', $scopedMemberIds)->get();
            foreach ($rawMemberSubs as $s) {
                $key = $s->event . ':' . $s->scope_type . ':' . ($s->scope_id ?? '');
                $memberSubsMap[$s->member_id][$key] = true;
            }
        }

        return view('orgportal::portal.settings', [
            'mailbox'            => $mailbox,
            'mailbox_id'         => $mailbox_id,
            'customer'           => $customer,
            'member'             => $member,
            'organization'       => $org,
            'units'              => $units,
            'members'            => $members,
            'canManageStructure' => $member->isGlobalManager(),
            'canGrantGlobal'     => (bool) $member->can_manage_org,
            'subsMap'            => $subsMap,
            'memberSubsMap'      => $memberSubsMap,
        ]);
    }

    public function saveSettings(Request $request, string $mailbox_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();
        $member   = $this->requireManager($customer, $mailbox);

        // Delete all existing subscriptions and re-insert from form.
        \Modules\OrgPortal\Models\OrgNotificationSubscription::where('member_id', $member->id)->delete();

        $events = [
            \Modules\OrgPortal\Models\OrgNotificationSubscription::EVENT_NEW_TICKET,
            \Modules\OrgPortal\Models\OrgNotificationSubscription::EVENT_REPLY_AGENT,
            \Modules\OrgPortal\Models\OrgNotificationSubscription::EVENT_REPLY_CUSTOMER,
        ];

        $subs = $request->input('subs', []);
        $org  = $member->organization;
        $org->load('units');

        foreach ($events as $event) {
            if (empty($subs[$event])) continue;
            foreach ($subs[$event] as $scopeKey => $val) {
                if ($member->isGlobalManager() && $scopeKey === 'org') {
                    \Modules\OrgPortal\Models\OrgNotificationSubscription::create([
                        'member_id'  => $member->id,
                        'event'      => $event,
                        'scope_type' => 'org',
                        'scope_id'   => null,
                    ]);
                } elseif (str_starts_with($scopeKey, 'unit_')) {
                    $unitId = (int) substr($scopeKey, 5);
                    // Verify unit belongs to this org and manager can access it.
                    $unitOk = $org->units->contains('id', $unitId);
                    if ($unitOk && ($member->isGlobalManager() || $member->unit_id === $unitId)) {
                        \Modules\OrgPortal\Models\OrgNotificationSubscription::create([
                            'member_id'  => $member->id,
                            'event'      => $event,
                            'scope_type' => 'unit',
                            'scope_id'   => $unitId,
                        ]);
                    }
                }
            }
        }

        // Per-member subscriptions managed on behalf of other members.
        $memberSubs = $request->input('member_subs', []);
        if (!empty($memberSubs)) {
            $org->loadMissing('units');
            $allUnitIds = $org->units->pluck('id')->toArray();

            foreach ($memberSubs as $targetMemberId => $eventsData) {
                $targetMemberId = (int) $targetMemberId;

                // Verify target member belongs to this org.
                $targetMember = OrganizationMember::where('id', $targetMemberId)
                    ->where('organization_id', $org->id)
                    ->where('is_active', true)
                    ->first();
                if (!$targetMember) continue;

                // Current manager must have scope access: global sees all, unit manager only their unit.
                if ($member->isUnitManager() && $member->unit_id !== $targetMember->unit_id) continue;

                \Modules\OrgPortal\Models\OrgNotificationSubscription::where('member_id', $targetMemberId)->delete();

                foreach ($events as $event) {
                    if (empty($eventsData[$event])) continue;
                    foreach ($eventsData[$event] as $scopeKey => $val) {
                        if (str_starts_with($scopeKey, 'unit_')) {
                            $unitId = (int) substr($scopeKey, 5);
                            if (in_array($unitId, $allUnitIds) && $unitId === $targetMember->unit_id) {
                                \Modules\OrgPortal\Models\OrgNotificationSubscription::create([
                                    'member_id'  => $targetMemberId,
                                    'event'      => $event,
                                    'scope_type' => 'unit',
                                    'scope_id'   => $unitId,
                                ]);
                            }
                        }
                    }
                }
            }
        }

        return redirect()
            ->route('orgportal.portal.settings', ['mailbox_id' => $mailbox_id])
            ->with('flash_success', __('orgportal::messages.settings_saved'));
    }

    // ─── Structure management (global manager only) ──────────────────────────

    /**
     * Only a global manager (manager with no unit) may manage org structure.
     */
    protected function requireGlobalManager(Customer $customer, Mailbox $mailbox): OrganizationMember
    {
        $member = $this->requireManager($customer, $mailbox);

        if (!$member->isGlobalManager()) {
            abort(403, __('orgportal::messages.access_denied'));
        }

        return $member;
    }

    public function createUnit(Request $request, string $mailbox_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();
        $member   = $this->requireGlobalManager($customer, $mailbox);

        $request->validate(['name' => 'required|string|max:255']);
        $name = trim($request->input('name'));

        $exists = OrganizationUnit::where('organization_id', $member->organization_id)
            ->where('name', $name)->exists();

        if ($exists) {
            return redirect()
                ->route('orgportal.portal.settings', ['mailbox_id' => $mailbox_id, 'tab' => 'units'])
                ->with('flash_error', __('orgportal::messages.unit_exists'));
        }

        OrganizationUnit::create([
            'organization_id' => $member->organization_id,
            'name'            => $name,
        ]);

        return redirect()
            ->route('orgportal.portal.settings', ['mailbox_id' => $mailbox_id, 'tab' => 'units'])
            ->with('flash_success', __('orgportal::messages.unit_created'));
    }

    public function renameUnit(Request $request, string $mailbox_id, int $unit_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();
        $member   = $this->requireGlobalManager($customer, $mailbox);

        $request->validate(['name' => 'required|string|max:255']);
        $name = trim($request->input('name'));

        $unit = OrganizationUnit::where('organization_id', $member->organization_id)
            ->findOrFail($unit_id);

        $exists = OrganizationUnit::where('organization_id', $member->organization_id)
            ->where('name', $name)
            ->where('id', '!=', $unit->id)
            ->exists();

        if ($exists) {
            return redirect()
                ->route('orgportal.portal.settings', ['mailbox_id' => $mailbox_id, 'tab' => 'units'])
                ->with('flash_error', __('orgportal::messages.unit_exists'));
        }

        $unit->name = $name;
        $unit->save();

        return redirect()
            ->route('orgportal.portal.settings', ['mailbox_id' => $mailbox_id, 'tab' => 'units'])
            ->with('flash_success', __('orgportal::messages.unit_updated'));
    }

    public function deleteUnit(Request $request, string $mailbox_id, int $unit_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();
        $member   = $this->requireGlobalManager($customer, $mailbox);

        $unit = OrganizationUnit::where('organization_id', $member->organization_id)
            ->findOrFail($unit_id);

        // Demote this unit's managers to plain members first — otherwise the
        // FK set-null would silently turn them into GLOBAL managers.
        OrganizationMember::where('unit_id', $unit->id)
            ->where('role', 'manager')
            ->update(['role' => 'member']);

        // Remaining members get unit_id = NULL via the FK on delete.
        $unit->delete();

        return redirect()
            ->route('orgportal.portal.settings', ['mailbox_id' => $mailbox_id, 'tab' => 'units'])
            ->with('flash_success', __('orgportal::messages.unit_deleted'));
    }

    /**
     * Update a member's unit and role.
     * role=manager + unit  => unit manager.
     * role=manager + no unit => global manager (requires can_manage_org).
     */
    public function updateMember(Request $request, string $mailbox_id, int $member_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();
        $manager  = $this->requireGlobalManager($customer, $mailbox);

        $request->validate([
            'unit_id' => 'nullable|integer',
            'role'    => 'required|in:member,manager',
        ]);

        $target = OrganizationMember::where('organization_id', $manager->organization_id)
            ->findOrFail($member_id);

        $unitId = (int) $request->input('unit_id') ?: null;
        $role   = $request->input('role');

        if ($unitId) {
            $unitOk = OrganizationUnit::where('organization_id', $manager->organization_id)
                ->where('id', $unitId)->exists();
            if (!$unitOk) {
                abort(422, __('orgportal::messages.access_denied'));
            }
        }

        // Promoting to global manager requires the explicit admin-granted right.
        if ($role === 'manager' && $unitId === null && !$manager->can_manage_org) {
            return redirect()
                ->route('orgportal.portal.settings', ['mailbox_id' => $mailbox_id, 'tab' => 'members'])
                ->with('flash_error', __('orgportal::messages.cannot_grant_global'));
        }

        $target->unit_id = $unitId;
        $target->role    = $role;
        $target->save();

        return redirect()
            ->route('orgportal.portal.settings', ['mailbox_id' => $mailbox_id, 'tab' => 'members'])
            ->with('flash_success', __('orgportal::messages.member_updated'));
    }

    /**
     * Deactivate ("fire") or reactivate a member. Deactivated members keep
     * their history but can no longer be assigned as a ticket author.
     */
    public function toggleMemberActive(Request $request, string $mailbox_id, int $member_id)
    {
        $mailbox  = $this->getMailbox($mailbox_id);
        $customer = $this->authCustomer();
        $manager  = $this->requireGlobalManager($customer, $mailbox);

        $target = OrganizationMember::where('organization_id', $manager->organization_id)
            ->findOrFail($member_id);

        if ($target->customer_id === $customer->id) {
            return redirect()
                ->route('orgportal.portal.settings', ['mailbox_id' => $mailbox_id, 'tab' => 'members'])
                ->with('flash_error', __('orgportal::messages.cannot_deactivate_self'));
        }

        $target->is_active      = !$target->is_active;
        $target->deactivated_at = $target->is_active ? null : now();
        $target->save();

        return redirect()
            ->route('orgportal.portal.settings', ['mailbox_id' => $mailbox_id, 'tab' => 'members'])
            ->with('flash_success', $target->is_active
                ? __('orgportal::messages.member_activated')
                : __('orgportal::messages.member_deactivated'));
    }
}

<?php

namespace Modules\OrgPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customer;
use Modules\OrgPortal\Models\Organization;
use Modules\OrgPortal\Models\OrganizationMember;
use Modules\OrgPortal\Providers\OrgPortalServiceProvider;

class OrgPortalAdminController extends Controller
{
    public function __construct()
    {
        // Every action requires either admin or the "manage organizations" permission.
        $this->middleware(function ($request, $next) {
            if (!OrgPortalServiceProvider::userCanManageOrganizations(auth()->user())) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Admin-only guard for destructive / system actions (delete org, settings,
     * impersonation). Permitted non-admin managers must not reach these.
     */
    protected function authorizeAdmin()
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403);
        }
    }

    public function index()
    {
        $organizations = Organization::withCount('members')->with('mailbox')->orderBy('name')->paginate(20);

        return view('orgportal::admin.index', [
            'organizations'           => $organizations,
            'show_badge_conversation' => (bool) \Option::get('orgportal.show_badge_conversation', true),
            'show_badge_kanban'       => (bool) \Option::get('orgportal.show_badge_kanban', true),
        ]);
    }

    public function create()
    {
        $mailboxes = \App\Mailbox::orderBy('name')->get(['id', 'name']);
        return view('orgportal::admin.create', compact('mailboxes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255|unique:organizations,name',
            'color'      => 'nullable|string|max:20|regex:/^#[0-9a-fA-F]{3,6}$/',
            'mailbox_id' => 'nullable|integer|exists:mailboxes,id',
        ]);

        Organization::create([
            'name'       => $request->input('name'),
            'color'      => $request->input('color') ?: null,
            'mailbox_id' => $request->input('mailbox_id') ?: null,
        ]);

        return redirect()->route('orgportal.admin.index')
            ->with('flash_success', __('orgportal::messages.org_created'));
    }

    public function edit(int $id)
    {
        $organization = Organization::findOrFail($id);
        $members      = $organization->members()->with(['customer.emails'])->get();
        $mailboxes    = \App\Mailbox::orderBy('name')->get(['id', 'name']);

        return view('orgportal::admin.edit', compact('organization', 'members', 'mailboxes'));
    }

    public function update(Request $request, int $id)
    {
        $organization = Organization::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:255|unique:organizations,name,' . $id,
            'color'      => 'nullable|string|max:20|regex:/^#[0-9a-fA-F]{3,6}$/',
            'mailbox_id' => 'nullable|integer|exists:mailboxes,id',
        ]);

        $organization->update([
            'name'       => $request->input('name'),
            'color'      => $request->input('color') ?: null,
            'mailbox_id' => $request->input('mailbox_id') ?: null,
        ]);

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', __('orgportal::messages.org_updated'));
    }

    public function destroy(int $id)
    {
        // Deleting organizations is admin-only, even for permitted managers.
        $this->authorizeAdmin();

        Organization::findOrFail($id)->delete();

        return redirect()->route('orgportal.admin.index')
            ->with('flash_success', __('orgportal::messages.org_deleted'));
    }

    public function addMember(Request $request, int $id)
    {
        $organization = Organization::findOrFail($id);

        $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'role'        => 'required|in:member,manager',
        ]);

        $customerId = (int) $request->input('customer_id');

        // Prevent duplicate membership
        if (OrganizationMember::where('organization_id', $id)
            ->where('customer_id', $customerId)->exists()
        ) {
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.already_member'));
        }

        // One org per customer — check they're not in another org
        if (OrganizationMember::where('customer_id', $customerId)->exists()) {
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.already_in_org'));
        }

        try {
            OrganizationMember::create([
                'organization_id' => $id,
                'customer_id'     => $customerId,
                'role'            => $request->input('role'),
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Unique constraint: concurrent request already added this customer
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.already_member'));
        }

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', __('orgportal::messages.member_added'));
    }

    public function updateMemberRole(Request $request, int $id, int $memberId)
    {
        $request->validate(['role' => 'required|in:member,manager']);

        OrganizationMember::where('id', $memberId)
            ->where('organization_id', $id)
            ->firstOrFail()
            ->update(['role' => $request->input('role')]);

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', __('orgportal::messages.role_updated'));
    }

    public function removeMember(int $id, int $memberId)
    {
        Organization::findOrFail($id);
        OrganizationMember::where('id', $memberId)
            ->where('organization_id', $id)
            ->firstOrFail()
            ->delete();

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', __('orgportal::messages.member_removed'));
    }

    public function settings()
    {
        $this->authorizeAdmin();

        return view('orgportal::admin.settings', [
            'show_badge_conversation' => (bool) \Option::get('orgportal.show_badge_conversation', true),
            'show_badge_kanban'       => (bool) \Option::get('orgportal.show_badge_kanban', true),
        ]);
    }

    public function saveSettings(Request $request)
    {
        $this->authorizeAdmin();

        \Option::set('orgportal.show_badge_conversation', (bool) $request->input('show_badge_conversation'));
        \Option::set('orgportal.show_badge_kanban', (bool) $request->input('show_badge_kanban'));

        return redirect()->route('orgportal.admin.settings')
            ->with('flash_success', __('orgportal::messages.settings_saved'));
    }

    public function mailboxSettings(int $id)
    {
        $this->authorizeAdmin();

        $mailbox = \App\Mailbox::findOrFail($id);
        $mailbox_id = $id;
        $optionKey = 'orgportal.company_filters_' . $mailbox_id;
        $raw = \Option::get($optionKey, '[]');
        $companyFilters = is_array($raw) ? $raw : (json_decode($raw, true) ?: []);

        $kanbanColumns = [];
        if (\Module::isActive('kanban')) {
            $boards = \Modules\Kanban\Entities\KnBoard::where(function ($q) use ($mailbox_id) {
                $q->where('mailbox_id', $mailbox_id)->orWhereNull('mailbox_id');
            })->get();
            $seen = [];
            foreach ($boards as $board) {
                foreach ((array) $board->columns as $col) {
                    $colId = (int) ($col['id'] ?? 0);
                    if (!$colId || isset($seen[$colId])) {
                        continue;
                    }
                    $seen[$colId] = true;
                    $kanbanColumns[] = [
                        'id'         => $colId,
                        'name'       => $col['name'] ?? "Column $colId",
                        'board_name' => $board->name ?? '',
                    ];
                }
            }
            usort($kanbanColumns, fn($a, $b) => $a['id'] <=> $b['id']);
        }

        $perConv   = \Option::get('orgportal.show_badge_conversation_' . $mailbox_id);
        $perKanban = \Option::get('orgportal.show_badge_kanban_' . $mailbox_id);
        $show_badge_conversation = $perConv !== null
            ? (bool) $perConv
            : (bool) \Option::get('orgportal.show_badge_conversation', true);
        $show_badge_kanban = $perKanban !== null
            ? (bool) $perKanban
            : (bool) \Option::get('orgportal.show_badge_kanban', true);

        return view('orgportal::admin.mailbox_settings', [
            'mailbox'                 => $mailbox,
            'companyFilters'          => $companyFilters,
            'kanbanColumns'           => $kanbanColumns,
            'show_badge_conversation' => $show_badge_conversation,
            'show_badge_kanban'       => $show_badge_kanban,
        ]);
    }

    public function saveMailboxSettings(Request $request, int $id)
    {
        $this->authorizeAdmin();

        \App\Mailbox::findOrFail($id);

        $filters = [];
        $selectedIds  = $request->input('company_filter_ids', []);
        $columnLabels = $request->input('company_filter_labels', []);
        foreach ($selectedIds as $sid) {
            $sid   = (int) $sid;
            $label = trim($columnLabels[$sid] ?? '');
            if ($sid > 0 && $label !== '') {
                $filters[] = ['id' => $sid, 'label' => $label];
            }
        }
        \Option::set('orgportal.company_filters_' . $id, json_encode($filters));

        \Option::set('orgportal.show_badge_conversation_' . $id, (bool) $request->input('show_badge_conversation'));
        \Option::set('orgportal.show_badge_kanban_' . $id, (bool) $request->input('show_badge_kanban'));

        return redirect()->route('orgportal.admin.mailbox-settings', $id)
            ->with('flash_success', __('orgportal::messages.settings_saved'));
    }

    /**
     * Generate and redirect to a portal login link for a given customer (admin only).
     * Usage: /orgportal/admin/impersonate/{customer_id}/{mailbox_id}
     */
    public function impersonatePortalLink(int $customer_id, int $mailbox_id)
    {
        $this->authorizeAdmin();

        $customer = Customer::findOrFail($customer_id);
        $mailbox  = \App\Mailbox::findOrFail($mailbox_id);

        $link = route('enduserportal.login_from_email', [
            'id'          => \EndUserPortal::encodeMailboxId($mailbox->id),
            'customer_id' => encrypt($customer->id),
            'hash'        => \EndUserPortal::customerHash($customer->created_at),
            'timestamp'   => encrypt(time()),
        ]);

        return redirect($link);
    }

    /**
     * AJAX: search customers by name or email for the member-add form.
     * Excludes customers already in any organization (one org per customer rule).
     */
    public function searchCustomers(Request $request)
    {
        $query = trim($request->input('q', ''));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $customers = Customer::where(function ($q) use ($query) {
                $q->where(function ($q2) use ($query) {
                    $q2->where('first_name', 'like', "%{$query}%")
                       ->orWhere('last_name', 'like', "%{$query}%");
                })
                ->orWhereHas('emails', function ($q2) use ($query) {
                    $q2->where('email', 'like', "%{$query}%");
                });
            })
            ->whereNotIn('id', OrganizationMember::select('customer_id'))
            ->with('emails')
            ->orderBy('last_name')
            ->limit(25)
            ->get(['id', 'first_name', 'last_name']);

        return response()->json(
            $customers->map(fn ($c) => [
                'id'    => $c->id,
                'name'  => $c->getFullName() . ' (#' . $c->id . ')',
                'email' => optional($c->emails->first())->email ?? '',
            ])
        );
    }

    public function apiDocs()
    {
        return view('orgportal::admin.api_docs');
    }
}

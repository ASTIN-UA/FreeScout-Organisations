<?php

namespace Modules\OrgPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customer;
use Modules\OrgPortal\Models\Organization;
use Modules\OrgPortal\Models\OrganizationMember;
use Modules\OrgPortal\Models\OrganizationUnit;
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

    protected function authorizeManage()
    {
        if (!auth()->user() || !\Modules\OrgPortal\Providers\OrgPortalServiceProvider::userCanManageOrganizations(auth()->user())) {
            abort(403);
        }
    }

    protected function authorizeTemplates()
    {
        if (!auth()->user() || !\Modules\OrgPortal\Providers\OrgPortalServiceProvider::userCanManageTemplates(auth()->user())) {
            abort(403);
        }
    }

    public function index()
    {
        $this->authorizeManage();

        $organizations = Organization::withCount('members')->with('mailbox')->orderBy('name')->paginate(20);

        $SP = \Modules\OrgPortal\Providers\OrgPortalServiceProvider::class;
        $canManageTemplates = $SP::userCanManageTemplates(auth()->user());

        $tplEvents    = [];
        $tplTemplates = [];
        if ($canManageTemplates) {
            $tplEvents = [
                'new_ticket'     => __('orgportal::messages.notif_event_new_ticket'),
                'reply_agent'    => __('orgportal::messages.notif_event_reply_agent'),
                'reply_customer' => __('orgportal::messages.notif_event_reply_customer'),
            ];
            foreach (array_keys($tplEvents) as $event) {
                $tplTemplates[$event] = [
                    'subject' => \Option::get('orgportal.tpl_' . $event . '_subject', ''),
                    'body'    => \Option::get('orgportal.tpl_' . $event . '_body', ''),
                ];
            }
        }

        return view('orgportal::admin.index', [
            'organizations'      => $organizations,
            'tplEvents'          => $tplEvents,
            'tplTemplates'       => $tplTemplates,
            'tplDefaults'        => $canManageTemplates ? self::defaultTemplates() : [],
            'canManageTemplates' => $canManageTemplates,
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
        $members      = $organization->members()->with(['customer.emails', 'unit'])->get();
        $units        = $organization->units()->orderBy('name')->get();
        $mailboxes    = \App\Mailbox::orderBy('name')->get(['id', 'name']);

        return view('orgportal::admin.edit', compact('organization', 'members', 'units', 'mailboxes'));
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
            'customer_id'    => 'required|integer|exists:customers,id',
            'role'           => 'required|in:member,manager',
            'unit_id'        => 'nullable|integer',
            'can_manage_org' => 'nullable|boolean',
        ]);

        $customerId = (int) $request->input('customer_id');
        $unitId     = (int) $request->input('unit_id') ?: null;

        // Prevent duplicate membership
        if (OrganizationMember::where('organization_id', $id)
            ->where('customer_id', $customerId)->exists()
        ) {
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.already_member'));
        }

        // One ACTIVE membership per customer — block only if they are an active
        // member elsewhere. Inactive (historical) memberships are allowed.
        if (OrganizationMember::where('customer_id', $customerId)->where('is_active', true)->exists()) {
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.already_in_org'));
        }

        // Unit (if any) must belong to this organization.
        if ($unitId && !OrganizationUnit::where('organization_id', $id)->where('id', $unitId)->exists()) {
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.unit_exists'));
        }

        try {
            OrganizationMember::create([
                'organization_id' => $id,
                'customer_id'     => $customerId,
                'unit_id'         => $unitId,
                'role'            => $request->input('role'),
                'can_manage_org'  => (bool) $request->input('can_manage_org', false),
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
        $request->validate([
            'role'           => 'required|in:member,manager',
            'unit_id'        => 'nullable|integer',
            'can_manage_org' => 'nullable|boolean',
        ]);

        $member = OrganizationMember::where('id', $memberId)
            ->where('organization_id', $id)
            ->firstOrFail();

        $unitId = (int) $request->input('unit_id') ?: null;

        if ($unitId && !OrganizationUnit::where('organization_id', $id)->where('id', $unitId)->exists()) {
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.unit_exists'));
        }

        $member->update([
            'role'           => $request->input('role'),
            'unit_id'        => $unitId,
            'can_manage_org' => (bool) $request->input('can_manage_org', false),
        ]);

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', __('orgportal::messages.role_updated'));
    }

    public function toggleMemberActive(int $id, int $memberId)
    {
        $member = OrganizationMember::where('id', $memberId)
            ->where('organization_id', $id)
            ->firstOrFail();

        $member->is_active      = !$member->is_active;
        $member->deactivated_at = $member->is_active ? null : now();
        $member->save();

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', $member->is_active
                ? __('orgportal::messages.member_activated')
                : __('orgportal::messages.member_deactivated'));
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

    // ─── Structural units ────────────────────────────────────────────────────

    public function addUnit(Request $request, int $id)
    {
        $organization = Organization::findOrFail($id);

        $request->validate(['name' => 'required|string|max:255']);
        $name = trim($request->input('name'));

        if (OrganizationUnit::where('organization_id', $id)->where('name', $name)->exists()) {
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.unit_exists'));
        }

        OrganizationUnit::create(['organization_id' => $id, 'name' => $name]);

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', __('orgportal::messages.unit_created'));
    }

    public function renameUnit(Request $request, int $id, int $unitId)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $name = trim($request->input('name'));

        $unit = OrganizationUnit::where('organization_id', $id)->findOrFail($unitId);

        if (OrganizationUnit::where('organization_id', $id)
            ->where('name', $name)->where('id', '!=', $unit->id)->exists()
        ) {
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.unit_exists'));
        }

        $unit->name = $name;
        $unit->save();

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', __('orgportal::messages.unit_updated'));
    }

    public function deleteUnit(int $id, int $unitId)
    {
        $unit = OrganizationUnit::where('organization_id', $id)->findOrFail($unitId);

        // Demote unit managers before delete so the FK set-null doesn't promote
        // them to global managers.
        OrganizationMember::where('unit_id', $unit->id)
            ->where('role', 'manager')
            ->update(['role' => 'member']);

        $unit->delete();

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', __('orgportal::messages.unit_deleted'));
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
        $show_badge_conversation = $perConv !== null ? (bool) $perConv : true;
        $show_badge_kanban       = $perKanban !== null ? (bool) $perKanban : true;

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

    public function globalSettings()
    {
        $this->authorizeTemplates();
        return redirect()->route('orgportal.admin.index', ['tab' => 'templates']);
    }

    public function saveGlobalSettings(Request $request)
    {
        $this->authorizeTemplates();

        foreach (['new_ticket', 'reply_agent', 'reply_customer'] as $event) {
            \Option::set('orgportal.tpl_' . $event . '_subject',
                strip_tags((string) $request->input('tpl_' . $event . '_subject', '')));
            \Option::set('orgportal.tpl_' . $event . '_body',
                self::sanitizeHtml((string) $request->input('tpl_' . $event . '_body', '')));
        }

        return redirect()->route('orgportal.admin.index', ['tab' => 'templates'])
            ->with('flash_success', __('orgportal::messages.settings_saved'));
    }

    public static function defaultTemplates(?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        $isUk   = ($locale === 'uk');

        $wrap = function (string $content) use ($isUk): string {
            $footer = $isUk
                ? 'Ви отримали цей лист, оскільки увімкнули сповіщення для вашої організації в Клієнтському порталі.'
                : 'You received this email because you enabled notifications for your organization in the Customer Portal.';
            return '<div style="font-family:Arial,sans-serif;font-size:14px;color:#333;max-width:600px;">'
                . $content
                . '<p style="margin-top:32px;font-size:12px;color:#999;">' . $footer . '</p>'
                . '</div>';
        };

        $btn = '<p><a href="{ticket_url}" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">'
            . ($isUk ? 'Переглянути заявку' : 'View Ticket')
            . '</a></p>';

        $authorCell = '<strong>{author_name}</strong> <span style="color:#999;font-size:12px;">({unit_name})</span>';

        $row = fn (string $l, string $v) =>
            '<tr><td style="color:#666;width:140px;padding:6px 0;">' . $l . ':</td>'
            . '<td style="padding:6px 0;">' . $v . '</td></tr>';

        $table = fn (array $rows) =>
            '<table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px;">'
            . implode('', $rows) . '</table>';

        if ($isUk) {
            return [
                'new_ticket' => [
                    'subject' => 'Нова заявка {ticket_number} від {author_name}',
                    'body'    => $wrap(
                        '<p>Доброго дня, <strong>{manager_name}</strong>!</p>'
                        . '<p>Учасник вашої організації <strong>{org_name}</strong> відкрив нову заявку:</p>'
                        . $table([
                            $row('Від', $authorCell),
                            $row('Тема', '<strong>{subject}</strong>'),
                            $row('Заявка №', '{ticket_number}'),
                            $row('Дата', '{created_datetime}'),
                        ])
                        . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{ticket_text}</div>'
                        . $btn
                    ),
                ],
                'reply_agent' => [
                    'subject' => 'Re: {ticket_number} — {subject}',
                    'body'    => $wrap(
                        '<p>Доброго дня, <strong>{manager_name}</strong>!</p>'
                        . '<p>Агент підтримки відповів на заявку у вашій організації <strong>{org_name}</strong>:</p>'
                        . $table([
                            $row('Клієнт', $authorCell),
                            $row('Тема', '<strong>{subject}</strong>'),
                            $row('Заявка №', '{ticket_number}'),
                            $row('Час відповіді', '{reply_datetime}'),
                        ])
                        . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{reply_text}</div>'
                        . $btn
                    ),
                ],
                'reply_customer' => [
                    'subject' => 'Re: {ticket_number} — {subject}',
                    'body'    => $wrap(
                        '<p>Доброго дня, <strong>{manager_name}</strong>!</p>'
                        . '<p>Клієнт відповів на заявку у вашій організації <strong>{org_name}</strong>:</p>'
                        . $table([
                            $row('Від', $authorCell),
                            $row('Тема', '<strong>{subject}</strong>'),
                            $row('Заявка №', '{ticket_number}'),
                            $row('Час відповіді', '{reply_datetime}'),
                        ])
                        . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{reply_text}</div>'
                        . $btn
                    ),
                ],
            ];
        }

        return [
            'new_ticket' => [
                'subject' => 'New ticket {ticket_number} from {author_name}',
                'body'    => $wrap(
                    '<p>Hello, <strong>{manager_name}</strong>!</p>'
                    . '<p>A new support ticket has been submitted by a member of your organization <strong>{org_name}</strong>:</p>'
                    . $table([
                        $row('From', $authorCell),
                        $row('Subject', '<strong>{subject}</strong>'),
                        $row('Ticket #', '{ticket_number}'),
                        $row('Date', '{created_datetime}'),
                    ])
                    . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{ticket_text}</div>'
                    . $btn
                ),
            ],
            'reply_agent' => [
                'subject' => 'Re: {ticket_number} — {subject}',
                'body'    => $wrap(
                    '<p>Hello, <strong>{manager_name}</strong>!</p>'
                    . '<p>A support agent has replied to a ticket in your organization <strong>{org_name}</strong>:</p>'
                    . $table([
                        $row('Customer', $authorCell),
                        $row('Subject', '<strong>{subject}</strong>'),
                        $row('Ticket #', '{ticket_number}'),
                        $row('Replied at', '{reply_datetime}'),
                    ])
                    . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{reply_text}</div>'
                    . $btn
                ),
            ],
            'reply_customer' => [
                'subject' => 'Re: {ticket_number} — {subject}',
                'body'    => $wrap(
                    '<p>Hello, <strong>{manager_name}</strong>!</p>'
                    . '<p>A customer has replied to a ticket in your organization <strong>{org_name}</strong>:</p>'
                    . $table([
                        $row('From', $authorCell),
                        $row('Subject', '<strong>{subject}</strong>'),
                        $row('Ticket #', '{ticket_number}'),
                        $row('Replied at', '{reply_datetime}'),
                    ])
                    . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{reply_text}</div>'
                    . $btn
                ),
            ],
        ];
    }

    protected static function sanitizeHtml(string $html): string
    {
        $html = preg_replace('/<\s*script\b[^>]*>.*?<\s*\/\s*script\s*>/is', '', $html);
        $html = preg_replace('/<\s*script\b[^>]*>/is', '', $html);
        $html = preg_replace('/\bon\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);
        $html = preg_replace('/\bon\w+\s*=\s*\S+/i', '', $html);
        $html = preg_replace('/href\s*=\s*["\']?\s*javascript\s*:/i', 'href="', $html);
        $html = preg_replace('/src\s*=\s*["\']?\s*javascript\s*:/i', 'src="', $html);
        return $html;
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
            ->whereNotIn('id', OrganizationMember::where('is_active', true)->select('customer_id'))
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

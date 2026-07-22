<?php

namespace Modules\OrgPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customer;
use Modules\OrgPortal\Models\Organization;
use Modules\OrgPortal\Models\OrganizationDomain;
use Modules\OrgPortal\Models\OrganizationMember;
use Modules\OrgPortal\Models\OrganizationUnit;
use Modules\OrgPortal\Providers\OrgPortalServiceProvider;
use Modules\OrgPortal\Services\MembershipService;
use Modules\OrgPortal\Services\OrgAttribution;

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

        $tagsActive = OrgAttribution::tagsModuleActive()
            && \Illuminate\Support\Facades\Schema::hasTable('organization_tags');

        $searchQuery = trim((string) request()->input('q', ''));

        $orgQuery = Organization::withCount(['members', 'conversations'])
            ->with('mailbox')
            ->orderBy('name');

        if ($tagsActive) {
            $orgQuery->withCount(['organizationTags as has_tags' => function ($q) {
                $q->select(\DB::raw('count(*)'));
            }]);
        }

        if (mb_strlen($searchQuery) >= 2) {
            $orgQuery->where('name', 'like', '%' . $searchQuery . '%');
        }

        $organizations = $orgQuery->paginate(20)->appends(array_filter(['q' => $searchQuery]));

        $SP = \Modules\OrgPortal\Providers\OrgPortalServiceProvider::class;
        $canManageTemplates = $SP::userCanManageTemplates(auth()->user());

        $isAdmin        = auth()->user() && auth()->user()->isAdmin();
        $systemStats    = $isAdmin ? OrgAttribution::stats() : null;
        $preflightStats = $isAdmin ? OrgAttribution::preflightStats() : null;

        $availableLocales     = $isAdmin ? $SP::getAvailablePortalLocales() : [];
        $langSwitcherEnabled  = (bool) \Option::get('orgportal.lang_switcher_enabled', false);
        $rawLocales           = \Option::get('orgportal.lang_switcher_locales', []);
        $langSwitcherLocales  = is_array($rawLocales) ? $rawLocales : (json_decode($rawLocales, true) ?: []);

        $tplEvents    = [];
        $tplTemplates = [];
        $tplDefaults  = [];
        $tplLocales   = [];
        if ($canManageTemplates) {
            $tplEvents = [
                'new_ticket'     => __('orgportal::messages.notif_event_new_ticket'),
                'reply_agent'    => __('orgportal::messages.notif_event_reply_agent'),
                'reply_customer' => __('orgportal::messages.notif_event_reply_customer'),
            ];
            // Locales shown in template editor = 'en' + enabled portal locales
            $tplLocales = array_unique(array_merge(['en'], $langSwitcherLocales));
            foreach ($tplLocales as $locale) {
                foreach (array_keys($tplEvents) as $event) {
                    $tplTemplates[$locale][$event] = [
                        'subject' => \Option::get('orgportal.tpl_' . $locale . '_' . $event . '_subject', ''),
                        'body'    => \Option::get('orgportal.tpl_' . $locale . '_' . $event . '_body', ''),
                    ];
                }
                $tplDefaults[$locale] = self::defaultTemplates($locale);
            }
        }

        return view('orgportal::admin.index', [
            'organizations'       => $organizations,
            'searchQuery'         => $searchQuery,
            'tplEvents'           => $tplEvents,
            'tplTemplates'        => $tplTemplates,
            'tplDefaults'         => $tplDefaults,
            'tplLocales'          => $tplLocales,
            'canManageTemplates'  => $canManageTemplates,
            'isAdmin'             => $isAdmin,
            'systemStats'         => $systemStats,
            'snapshotEnabled'     => OrgAttribution::snapshotEnabled(),
            'preflightStats'      => $preflightStats,
            'attributionSource'   => $isAdmin ? OrgAttribution::attributionSource() : 'member',
            'tagsModuleActive'    => $tagsActive,
            'availableLocales'    => $availableLocales,
            'langSwitcherEnabled' => $langSwitcherEnabled,
            'langSwitcherLocales' => $langSwitcherLocales,
        ]);
    }

    public function listOrganizationsJson(Request $request)
    {
        $this->authorizeManage();

        $q = trim((string) $request->input('q', ''));
        $tagsActive = OrgAttribution::tagsModuleActive()
            && \Illuminate\Support\Facades\Schema::hasTable('organization_tags');

        $query = Organization::withCount(['members', 'conversations'])
            ->with('mailbox')
            ->orderBy('name');

        if ($tagsActive) {
            $query->withCount(['organizationTags as has_tags' => function ($qb) {
                $qb->select(\DB::raw('count(*)'));
            }]);
        }

        if (mb_strlen($q) >= 2) {
            $query->where('name', 'like', '%' . $q . '%');
        }

        $isAdmin        = auth()->user() && auth()->user()->isAdmin();
        $snapshotEnabled = OrgAttribution::snapshotEnabled();
        $searchBase     = url(\Helper::getSubdirectory() . 'search');

        $orgs = $query->get()->map(function ($org) use ($isAdmin, $snapshotEnabled, $searchBase, $tagsActive) {
            return [
                'id'                 => $org->id,
                'name'               => $org->name,
                'mailbox_name'       => $org->mailbox ? $org->mailbox->name : null,
                'members_count'      => (int) $org->members_count,
                'conversations_count'=> (int) $org->conversations_count,
                'has_tags'           => $tagsActive ? (bool) $org->has_tags : null,
                'is_active'          => (bool) $org->is_active,
                'edit_url'           => route('orgportal.admin.edit', $org->id),
                'tickets_url'        => $searchBase . '?' . http_build_query(['f' => ['organization' => $org->id]]),
                'deactivate_url'     => route('orgportal.admin.deactivate', $org->id),
                'delete_url'         => route('orgportal.admin.destroy', $org->id),
                'is_admin'           => $isAdmin,
                'snapshot_enabled'   => $snapshotEnabled,
                'can_delete'         => $org->members_count === 0 && $org->conversations_count === 0,
            ];
        });

        return response()->json(['organizations' => $orgs, 'tags_active' => $tagsActive]);
    }

    public function runBackfill()
    {
        $this->authorizeAdmin();
        $result = OrgAttribution::backfillBatchDetailed(2000);
        return redirect()->route('orgportal.admin.index', ['tab' => 'system'])
            ->with('backfill_result', $result);
    }

    public function resetAttribution()
    {
        $this->authorizeAdmin();
        \App\Conversation::whereNotNull('customer_id')->update([
            'org_id'            => null,
            'org_unit_id'       => null,
            'org_attributed_at' => null,
        ]);
        return redirect()->route('orgportal.admin.index', ['tab' => 'system'])
            ->with('success', __('orgportal::messages.system_reset_done'));
    }

    public function saveSystemSettings(Request $request)
    {
        $this->authorizeAdmin();

        \Option::set('orgportal.snapshot_visibility', $request->input('snapshot_visibility') == '1' ? '1' : '0');

        $source = $request->input('attribution_source', 'member');
        if (!in_array($source, ['member', 'tag', 'tag_only'])) $source = 'member';
        if (in_array($source, ['tag', 'tag_only']) && !\Module::isActive('tags')) $source = 'member';
        \Option::set('orgportal.attribution_source', $source);

        \Option::set('orgportal.attribution_cron_enabled', $request->input('attribution_cron_enabled') == '1' ? '1' : '0');

        \Option::set('orgportal.lang_switcher_enabled', $request->input('lang_switcher_enabled') == '1' ? '1' : '0');

        $locales = $request->input('lang_switcher_locales', []);
        if (!is_array($locales)) $locales = [];
        \Option::set('orgportal.lang_switcher_locales', array_values($locales));

        return redirect()->route('orgportal.admin.index', ['tab' => 'system'])
            ->with('flash_success', __('orgportal::messages.settings_saved'));
    }

    public function create()
    {
        $mailboxes = \App\Mailbox::orderBy('name')->get(['id', 'name']);
        return view('orgportal::admin.create', compact('mailboxes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:191|unique:organizations,name',
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

        $tagsModuleActive = \Module::isActive('tags');
        $allTags          = collect();
        $boundTagIds      = [];
        $boundTagUnits    = [];
        if ($tagsModuleActive && \Schema::hasTable('tags') && \Schema::hasTable('organization_tags')) {
            $allTags     = \DB::table('tags')->orderBy('name')->get(['id', 'name']);
            $bindings    = \Modules\OrgPortal\Models\OrganizationTag::where('organization_id', $id)->get();
            $boundTagIds = $bindings->pluck('tag_id')->toArray();
            $boundTagUnits = $bindings->pluck('unit_id', 'tag_id')->toArray();
        }

        $domains = \Schema::hasTable('organization_domains')
            ? \Modules\OrgPortal\Models\OrganizationDomain::forOrg($id)
            : collect();

        return view('orgportal::admin.edit', compact(
            'organization', 'members', 'units', 'mailboxes',
            'tagsModuleActive', 'allTags', 'boundTagIds', 'boundTagUnits', 'domains'
        ));
    }

    public function update(Request $request, int $id)
    {
        $organization = Organization::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:191|unique:organizations,name,' . $id,
            'color'      => 'nullable|string|max:20|regex:/^#[0-9a-fA-F]{3,6}$/',
            'mailbox_id' => 'nullable|integer|exists:mailboxes,id',
        ]);

        // Domain management is admin-only. Changing the mailbox re-scopes every
        // domain binding of this organisation, so a permitted manager must not
        // reach it through this route either — clearing the mailbox would turn
        // a deliberately mailbox-confined binding into a global one.
        $newMailboxId = (int) ($request->input('mailbox_id') ?: 0);
        if ((int) $organization->mailbox_id !== $newMailboxId
            && \Schema::hasTable('organization_domains')
            && OrganizationDomain::where('organization_id', $id)->exists()
        ) {
            $this->authorizeAdmin();
        }

        $organization->update([
            'name'       => $request->input('name'),
            'color'      => $request->input('color') ?: null,
            'mailbox_id' => $request->input('mailbox_id') ?: null,
        ]);

        // Save tag bindings if Tags module active
        if (\Module::isActive('tags') && \Schema::hasTable('organization_tags')) {
            $tagIds = array_filter(array_map('intval', (array) $request->input('tag_ids', [])));
            if (!empty($tagIds) && \Schema::hasTable('tags')) {
                $tagIds = \DB::table('tags')->whereIn('id', $tagIds)->pluck('id')->map(fn ($i) => (int) $i)->all();
            }
            \Modules\OrgPortal\Models\OrganizationTag::where('organization_id', $id)->delete();
            foreach ($tagIds as $tagId) {
                \Modules\OrgPortal\Models\OrganizationTag::create([
                    'organization_id' => $id,
                    'tag_id'          => $tagId,
                    'unit_id'         => null,
                ]);
            }
        }

        // Keep the denormalised mailbox_id on domain bindings in step with the
        // organisation. Domains that would collide with a binding already
        // present in the target mailbox stay behind and are reported.
        $domainConflicts = [];
        if (\Schema::hasTable('organization_domains')) {
            $domainConflicts = OrganizationDomain::syncMailbox(
                $id,
                $request->input('mailbox_id') ?: null
            );
        }

        $redirect = redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', __('orgportal::messages.org_updated'));

        if ($domainConflicts) {
            $redirect->with('flash_error', __('orgportal::messages.domain_mailbox_conflict', [
                'domains' => implode(', ', $domainConflicts),
            ]));
        }

        return $redirect;
    }

    public function destroy(int $id)
    {
        // Deleting organizations is admin-only, even for permitted managers.
        $this->authorizeAdmin();

        $org = Organization::withCount(['members', 'conversations'])->findOrFail($id);

        if ($org->members_count > 0) {
            return redirect()->route('orgportal.admin.index')
                ->with('flash_error', __('orgportal::messages.org_delete_has_members', ['count' => $org->members_count]));
        }

        if ($org->conversations_count > 0) {
            return redirect()->route('orgportal.admin.index')
                ->with('flash_error', __('orgportal::messages.org_delete_has_tickets', ['count' => $org->conversations_count]));
        }

        $org->delete();

        return redirect()->route('orgportal.admin.index')
            ->with('flash_success', __('orgportal::messages.org_deleted'));
    }

    public function deactivateOrg(int $id)
    {
        $this->authorizeAdmin();

        $org = Organization::findOrFail($id);
        $org->is_active = !$org->is_active;
        $org->save();

        $msg = $org->is_active
            ? __('orgportal::messages.org_activated')
            : __('orgportal::messages.org_deactivated');

        return redirect()->route('orgportal.admin.index')
            ->with('flash_success', $msg);
    }

    /**
     * Bind an email domain to an organisation.
     *
     * Admin-only, unlike member management: one domain binding can enrol
     * hundreds of customers and hand them portal access to each other's
     * tickets, which is a wider blast radius than adding a single member.
     */
    public function addDomain(Request $request, int $id)
    {
        $this->authorizeAdmin();

        $organization = Organization::findOrFail($id);

        $request->validate(['domain' => 'required|string|max:191']);

        $domain = OrganizationDomain::normalize($request->input('domain'));

        if (!OrganizationDomain::isValidFormat($domain)) {
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.domain_invalid'));
        }

        if (OrganizationDomain::isPublicDomain($domain)) {
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.domain_public', ['domain' => $domain]));
        }

        $slot = $organization->mailbox_id ?: OrganizationDomain::GLOBAL_MAILBOX;

        $taken = OrganizationDomain::where('domain', $domain)->where('mailbox_id', $slot)->first();
        if ($taken) {
            $msg = (int) $taken->organization_id === $id
                ? __('orgportal::messages.domain_already_bound')
                : __('orgportal::messages.domain_taken', [
                    'org' => optional($taken->organization)->name ?: '#' . $taken->organization_id,
                ]);

            return redirect()->route('orgportal.admin.edit', $id)->with('flash_error', $msg);
        }

        OrganizationDomain::create([
            'organization_id' => $id,
            'mailbox_id'      => $slot,
            'domain'          => $domain,
        ]);

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', __('orgportal::messages.domain_added', ['domain' => $domain]));
    }

    /**
     * Remove a domain binding.
     *
     * Memberships the binding produced are kept by default: those customers may
     * already be using the portal, and silently cutting off dozens of people is
     * worse than an over-broad membership. Passing deactivate_members=1 opts
     * into the rollback — the reason organization_members.source exists.
     */
    public function removeDomain(Request $request, int $id, int $domainId)
    {
        $this->authorizeAdmin();

        $domain = OrganizationDomain::where('organization_id', $id)->findOrFail($domainId);
        $name   = $domain->domain;
        $domain->delete();

        $deactivated = 0;
        if ($request->input('deactivate_members')) {
            // Only members whose address actually belongs to the removed domain.
            // Deactivating every domain-sourced member would cut off people
            // enrolled by the organisation's OTHER, still-active domains.
            $affected = OrganizationMember::where('organization_id', $id)
                ->where('source', MembershipService::SOURCE_DOMAIN)
                ->where('is_active', true)
                ->pluck('customer_id');

            $matching = \DB::table('emails')
                ->whereIn('customer_id', $affected)
                ->get(['customer_id', 'email'])
                ->filter(fn ($row) => OrganizationDomain::fromEmail($row->email) === $name)
                ->pluck('customer_id')
                ->unique()
                ->values();

            if ($matching->isNotEmpty()) {
                $deactivated = OrganizationMember::where('organization_id', $id)
                    ->where('source', MembershipService::SOURCE_DOMAIN)
                    ->where('is_active', true)
                    ->whereIn('customer_id', $matching)
                    ->update(['is_active' => false, 'deactivated_at' => now()]);
            }
        }

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', $deactivated
                ? __('orgportal::messages.domain_removed_with_members', ['domain' => $name, 'count' => $deactivated])
                : __('orgportal::messages.domain_removed', ['domain' => $name]));
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

        $role         = $request->input('role');
        $canManageOrg = (bool) $request->input('can_manage_org', false);

        $result = \DB::transaction(function () use ($id, $customerId, $unitId, $role, $canManageOrg) {
            // One ACTIVE membership per customer — block only if they are an active
            // member elsewhere. Inactive (historical) memberships are allowed.
            if (OrganizationMember::where('customer_id', $customerId)->where('is_active', true)->exists()) {
                return 'already_in_org';
            }

            // Unit (if any) must belong to this organization.
            if ($unitId && !OrganizationUnit::where('organization_id', $id)->where('id', $unitId)->exists()) {
                return 'unit_not_found';
            }

            OrganizationMember::create([
                'organization_id' => $id,
                'customer_id'     => $customerId,
                'unit_id'         => $unitId,
                'role'            => $role,
                'can_manage_org'  => $canManageOrg,
            ]);

            return 'ok';
        });

        if ($result === 'already_in_org') {
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.already_in_org'));
        }
        if ($result === 'unit_not_found') {
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.unit_not_found'));
        }

        // Back-fill snapshot on existing un-attributed conversations for this customer.
        OrgAttribution::reattributeForCustomer($customerId, $id, $unitId);

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
                ->with('flash_error', __('orgportal::messages.unit_not_found'));
        }

        $member->update([
            'role'           => $request->input('role'),
            'unit_id'        => $unitId,
            'can_manage_org' => (bool) $request->input('can_manage_org', false),
        ]);

        // Sync org_unit_id on this member's existing tickets — a unit change
        // must move the ticket into the new unit scope, or unit-filtered
        // manager views won't find tickets attributed before the unit change.
        \App\Conversation::where('customer_id', $member->customer_id)
            ->where('org_id', $id)
            ->update(['org_unit_id' => $unitId]);

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
        $member = OrganizationMember::where('id', $memberId)
            ->where('organization_id', $id)
            ->firstOrFail();

        // Count only tickets attributed to this organisation — a customer may have
        // personal tickets predating membership (or from another org) that must not
        // block removal. Snapshot mode has org_id to scope by; legacy mode has no
        // per-ticket attribution, so fall back to the raw customer_id count.
        $ticketsQuery = \App\Conversation::where('customer_id', $member->customer_id);
        if (OrgAttribution::snapshotEnabled()) {
            $ticketsQuery->where('org_id', $id);
        }
        $ticketsCount = $ticketsQuery->count();
        if ($ticketsCount > 0) {
            return redirect()->route('orgportal.admin.edit', $id)
                ->with('flash_error', __('orgportal::messages.member_remove_has_tickets', ['count' => $ticketsCount]));
        }

        $member->delete();

        return redirect()->route('orgportal.admin.edit', $id)
            ->with('flash_success', __('orgportal::messages.member_removed'));
    }

    // ─── Structural units ────────────────────────────────────────────────────

    public function addUnit(Request $request, int $id)
    {
        $organization = Organization::findOrFail($id);

        $request->validate(['name' => 'required|string|max:191']);
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
        $request->validate(['name' => 'required|string|max:191']);
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

        // Migrate legacy format [{id, label}] → [{id, name, labels:{en:…}, sort}]
        $nameById = collect($kanbanColumns)->keyBy('id');
        foreach ($companyFilters as &$f) {
            if (!isset($f['labels'])) {
                $f['labels'] = ['en' => $f['label'] ?? ''];
                $f['name']   = $nameById[$f['id']]['name'] ?? '';
                unset($f['label']);
            }
            if (!isset($f['sort'])) {
                $f['sort'] = 0;
            }
        }
        unset($f);
        usort($companyFilters, fn($a, $b) => $a['sort'] <=> $b['sort']);

        $langSwitcherEnabled = (bool) \Option::get('orgportal.lang_switcher_enabled', false);
        $rawLocales          = \Option::get('orgportal.lang_switcher_locales', []);
        $parsedLocales       = is_array($rawLocales) ? $rawLocales : (json_decode($rawLocales, true) ?: []);
        $filterLocales       = array_unique(array_merge(
            ['en'],
            $langSwitcherEnabled ? $parsedLocales : []
        ));
        $localeNames = [];
        foreach ($filterLocales as $loc) {
            $localeNames[$loc] = \Modules\OrgPortal\Providers\OrgPortalServiceProvider::getLocaleName($loc);
        }

        $perConv     = \Option::get('orgportal.show_badge_conversation_' . $mailbox_id);
        $perKanban   = \Option::get('orgportal.show_badge_kanban_' . $mailbox_id);
        $perProfile  = \Option::get('orgportal.show_org_in_profile_' . $mailbox_id);
        $show_badge_conversation = $perConv    !== null ? (bool) $perConv    : true;
        $show_badge_kanban       = $perKanban  !== null ? (bool) $perKanban  : true;
        $show_org_in_profile     = $perProfile !== null ? (bool) $perProfile : true;

        $cfFields        = [];
        $cfFieldSettings = [];
        if (\Module::isActive('customfields')) {
            $cfFields = \Modules\CustomFields\Entities\CustomField::getMailboxCustomFields($mailbox_id)->all();
            $rawCf    = \Option::get('orgportal.cf_fields_' . $mailbox_id, '[]');
            $cfFieldSettings = is_array($rawCf) ? $rawCf : (json_decode($rawCf, true) ?: []);
        }

        return view('orgportal::admin.mailbox_settings', [
            'mailbox'                 => $mailbox,
            'companyFilters'          => $companyFilters,
            'kanbanColumns'           => $kanbanColumns,
            'filterLocales'           => $filterLocales,
            'localeNames'             => $localeNames,
            'show_badge_conversation' => $show_badge_conversation,
            'show_badge_kanban'       => $show_badge_kanban,
            'show_org_in_profile'     => $show_org_in_profile,
            'cfFields'                => $cfFields,
            'cfFieldSettings'         => $cfFieldSettings,
        ]);
    }

    public function saveMailboxSettings(Request $request, int $id)
    {
        $this->authorizeAdmin();

        \App\Mailbox::findOrFail($id);

        $filters      = [];
        $selectedIds  = $request->input('company_filter_ids', []);
        $columnLabels = $request->input('company_filter_labels', []);   // [colId][locale] = label
        $columnNames  = $request->input('company_filter_names', []);    // [colId] = original name
        $sortOrder    = $request->input('company_filter_sort', []);     // [colId] = sort index
        foreach ($selectedIds as $sid) {
            $sid = (int) $sid;
            if ($sid <= 0) continue;
            $labels = [];
            foreach ((array) ($columnLabels[$sid] ?? []) as $loc => $lbl) {
                $lbl = trim((string) $lbl);
                if ($lbl !== '') {
                    $labels[preg_replace('/[^a-zA-Z0-9_\-]/', '', $loc)] = $lbl;
                }
            }
            $filters[] = [
                'id'     => $sid,
                'name'   => strip_tags(trim($columnNames[$sid] ?? '')),
                'labels' => $labels,
                'sort'   => (int) ($sortOrder[$sid] ?? 999),
            ];
        }
        usort($filters, fn($a, $b) => $a['sort'] <=> $b['sort']);
        \Option::set('orgportal.company_filters_' . $id, json_encode($filters));

        \Option::set('orgportal.show_badge_conversation_' . $id, (bool) $request->input('show_badge_conversation'));
        \Option::set('orgportal.show_badge_kanban_' . $id, (bool) $request->input('show_badge_kanban'));
        \Option::set('orgportal.show_org_in_profile_' . $id, (bool) $request->input('show_org_in_profile'));

        // Custom Fields — save selected fields with labels and sort order
        if (\Module::isActive('customfields')) {
            $cfFields     = [];
            $cfSelectedIds = $request->input('cf_field_ids', []);
            $cfLabels      = $request->input('cf_field_labels', []);
            $cfSort        = $request->input('cf_field_sort', []);
            foreach ($cfSelectedIds as $fid) {
                $fid = (int) $fid;
                if ($fid <= 0) continue;
                $labels = [];
                foreach ((array) ($cfLabels[$fid] ?? []) as $loc => $lbl) {
                    $lbl = trim((string) $lbl);
                    if ($lbl !== '') {
                        $labels[preg_replace('/[^a-zA-Z0-9_\-]/', '', $loc)] = $lbl;
                    }
                }
                $cfFields[] = [
                    'id'     => $fid,
                    'labels' => $labels,
                    'sort'   => (int) ($cfSort[$fid] ?? 999),
                ];
            }
            usort($cfFields, fn($a, $b) => $a['sort'] <=> $b['sort']);
            \Option::set('orgportal.cf_fields_' . $id, json_encode($cfFields));
        }

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

        $locale = $request->input('tpl_locale', 'en');
        // Allowlist: only locales that exist as template files
        $validLocales = array_map(
            fn ($f) => basename($f, '.php'),
            glob(__DIR__ . '/../../Resources/templates/*.php') ?: []
        );
        if (!in_array($locale, $validLocales, true)) {
            $locale = 'en';
        }

        foreach (['new_ticket', 'reply_agent', 'reply_customer'] as $event) {
            \Option::set('orgportal.tpl_' . $locale . '_' . $event . '_subject',
                strip_tags((string) $request->input('tpl_' . $event . '_subject', '')));
            \Option::set('orgportal.tpl_' . $locale . '_' . $event . '_body',
                self::sanitizeHtml((string) $request->input('tpl_' . $event . '_body', '')));
        }

        return redirect()->route('orgportal.admin.index', ['tab' => 'templates'])
            ->with('flash_success', __('orgportal::messages.settings_saved'));
    }

    public static function defaultTemplates(?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        $dir    = __DIR__ . '/../../Resources/templates/';

        $path = $dir . $locale . '.php';
        if (file_exists($path)) {
            return require $path;
        }

        // Language-family fallback: pt-BR → pt-PT → en
        $base = explode('-', $locale)[0];
        foreach (glob($dir . $base . '*.php') ?: [] as $candidate) {
            return require $candidate;
        }

        $enPath = $dir . 'en.php';
        return file_exists($enPath) ? require $enPath : [];
    }

    protected static function sanitizeHtml(string $html): string
    {
        return \Purifier::clean($html);
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

    public function searchOrganizations(Request $request)
    {
        $query = trim($request->input('q', ''));

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $orgs = Organization::where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(25)
            ->get(['id', 'name']);

        return response()->json($orgs->map(fn ($o) => ['id' => $o->id, 'name' => $o->name]));
    }

    public function apiDocs()
    {
        return view('orgportal::admin.api_docs');
    }
}

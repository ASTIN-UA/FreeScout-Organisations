<?php

namespace Modules\OrgPortal\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\OrgPortal\Models\Organization;
use Modules\OrgPortal\Models\OrganizationMember;
use Modules\OrgPortal\Models\OrganizationTag;
use Modules\OrgPortal\Models\OrganizationUnit;

class OrgPortalApiController extends Controller
{
    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Parse an optional mailboxId field from the request.
     * Returns int on success, null when field is absent/null, sets $error on invalid input.
     */
    private function parseMailboxId(Request $request, string $field, ?string &$error): ?int
    {
        $error = null;
        $raw   = $request->input($field);

        if ($raw === null || $raw === '') {
            return null;
        }

        $id = (int) $raw;

        if ($id <= 0) {
            $error = 'mailboxId must be a positive integer or null.';
            return null;
        }

        if (!\App\Mailbox::where('id', $id)->exists()) {
            $error = 'Mailbox not found.';
            return null;
        }

        return $id;
    }

    /**
     * Longest name the `organizations`/`organization_units` name columns accept.
     * They are varchar(191) — the utf8mb4 index limit — not varchar(255).
     */
    const NAME_MAX_LENGTH = 191;

    /**
     * Parse an optional color field. Returns the hex string, or null when the
     * field is absent/empty; sets $error on malformed input. Mirrors the
     * validation the admin UI applies (OrgPortalAdminController::store()).
     */
    private function parseColor(Request $request, string $field, ?string &$error): ?string
    {
        $error = null;
        $raw   = $request->input($field);

        if ($raw === null || $raw === '') {
            return null;
        }

        $color = trim((string) $raw);

        if (!preg_match('/^#[0-9a-fA-F]{3,6}$/', $color)) {
            $error = 'color must be a hex color such as "#ff0000", or null.';
            return null;
        }

        return $color;
    }

    /**
     * Count a member's tickets that block hard-deleting their membership.
     *
     * Only tickets attributed to THIS organisation count — a customer may have
     * personal tickets predating membership (or belonging to another org) that
     * must not block removal. Snapshot mode stamps org_id on each ticket to
     * scope by; legacy mode has no per-ticket attribution, so it falls back to
     * the raw customer_id count. Mirrors OrgPortalAdminController::removeMember().
     */
    private function blockingTicketsCount(int $customerId, int $organizationId): int
    {
        $query = \App\Conversation::where('customer_id', $customerId);

        if (\Modules\OrgPortal\Services\OrgAttribution::snapshotEnabled()) {
            $query->where('org_id', $organizationId);
        }

        return $query->count();
    }

    private function errorResponse(string $message, int $status, array $errors = []): JsonResponse
    {
        $body = [
            'message'   => $message,
            'errorCode' => strtoupper(str_replace(' ', '_', $message)),
        ];

        if ($errors) {
            $body['_embedded'] = ['errors' => $errors];
        }

        return response()->json($body, $status);
    }

    private function orgToArray(Organization $org, bool $withMembers = false): array
    {
        $data = [
            'id'         => $org->id,
            'name'       => $org->name,
            'color'      => $org->color ?: null,
            'isActive'   => (bool) $org->is_active,
            'mailboxId'  => $org->mailbox_id,
            'createdAt'  => $org->created_at->toIso8601String(),
            'updatedAt'  => $org->updated_at->toIso8601String(),
        ];

        if ($withMembers) {
            $data['_embedded']['members'] = $org->members->map(fn ($m) => $this->memberToArray($m))->values()->all();
            $data['_embedded']['units']   = $org->units->map(fn ($u) => $this->unitToArray($u))->values()->all();
        }

        return $data;
    }

    private function unitToArray(OrganizationUnit $u): array
    {
        return [
            'id'             => $u->id,
            'organizationId' => $u->organization_id,
            'name'           => $u->name,
            'createdAt'      => $u->created_at->toIso8601String(),
            'updatedAt'      => $u->updated_at->toIso8601String(),
        ];
    }

    private function memberToArray(OrganizationMember $m): array
    {
        return [
            'id'                 => $m->id,
            'organizationId'     => $m->organization_id,
            'unitId'             => $m->unit_id,
            'customerId'         => $m->customer_id,
            'role'               => $m->role,
            'canManageOrg'       => (bool) $m->can_manage_org,
            'isActive'           => (bool) $m->is_active,
            'notifyOnNewTicket'  => (bool) $m->notify_on_new_ticket,
            'createdAt'          => $m->created_at->toIso8601String(),
            'updatedAt'          => $m->updated_at->toIso8601String(),
        ];
    }

    private function tagToArray(OrganizationTag $t): array
    {
        return [
            'id'             => $t->id,
            'organizationId' => $t->organization_id,
            'tagId'          => $t->tag_id,
            'unitId'         => $t->unit_id,
        ];
    }

    // ─── Organizations ───────────────────────────────────────────────────────

    /**
     * GET /api/organizations
     * List all organizations (paginated).
     */
    public function listOrganizations(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->input('pageSize', 25), 1), 100);
        $page    = max((int) $request->input('page', 1), 1);

        $query = Organization::orderBy('name');

        if ($request->filled('mailboxId')) {
            $mailboxId = (int) $request->input('mailboxId');
            $query->visibleInMailbox($mailboxId);
        }

        // exactName wins over name — it answers "does this exact organization
        // already exist?", which is what a client asks before falling back to
        // POST /api/organizations.
        if ($request->filled('exactName')) {
            $query->where('name', trim((string) $request->input('exactName')));
        } elseif ($request->filled('name')) {
            $needle = trim((string) $request->input('name'));
            // Escape LIKE wildcards so a literal % or _ in the search term
            // matches itself instead of acting as a pattern.
            $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $needle);
            $query->where('name', 'like', '%' . $escaped . '%');
        }

        if ($request->filled('isActive')) {
            $query->where('is_active', filter_var($request->input('isActive'), FILTER_VALIDATE_BOOLEAN));
        }

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            '_embedded' => [
                'organizations' => collect($paginator->items())
                    ->map(fn ($o) => $this->orgToArray($o))
                    ->values()
                    ->all(),
            ],
            'page' => [
                'size'          => $paginator->perPage(),
                'totalElements' => $paginator->total(),
                'totalPages'    => $paginator->lastPage(),
                'number'        => $paginator->currentPage(),
            ],
        ]);
    }

    /**
     * POST /api/organizations
     * Create a new organization.
     */
    public function createOrganization(Request $request): JsonResponse
    {
        $name = trim((string) $request->input('name', ''));

        if ($name === '') {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'name', 'message' => 'Name is required.', 'source' => 'JSON'],
            ]);
        }

        if (mb_strlen($name) > self::NAME_MAX_LENGTH) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'name', 'message' => 'Name may not exceed ' . self::NAME_MAX_LENGTH . ' characters.', 'source' => 'JSON'],
            ]);
        }

        if (Organization::where('name', $name)->exists()) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'name', 'message' => 'An organization with this name already exists.', 'source' => 'JSON'],
            ]);
        }

        $mailboxId = $this->parseMailboxId($request, 'mailboxId', $mbError);
        if ($mbError) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'mailboxId', 'message' => $mbError, 'source' => 'JSON'],
            ]);
        }

        $color = $this->parseColor($request, 'color', $colorError);
        if ($colorError) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'color', 'message' => $colorError, 'source' => 'JSON'],
            ]);
        }

        $org = Organization::create([
            'name'       => $name,
            'mailbox_id' => $mailboxId,
            'color'      => $color,
            'is_active'  => $request->has('isActive') ? (bool) $request->input('isActive') : true,
        ]);

        // The INSERT relies on column defaults for anything not passed above, and
        // the in-memory model does not know them — without this the response
        // would report isActive: false for an organization that is active in the
        // database.
        $org->refresh();

        return response()->json($this->orgToArray($org), 201)
            ->header('Resource-ID', $org->id);
    }

    /**
     * GET /api/organizations/{id}
     * Get a single organization including its members.
     */
    public function getOrganization(int $id): JsonResponse
    {
        $org = Organization::with('members', 'units')->find($id);

        if (!$org) {
            return $this->errorResponse('Organization not found.', 404);
        }

        return response()->json($this->orgToArray($org, true));
    }

    /**
     * PUT /api/organizations/{id}
     * Update organization name, color, mailboxId, or isActive.
     *
     * Body: { "name": "Acme", "color": "#ff0000"|null, "mailboxId": 1|null, "isActive": true }
     */
    public function updateOrganization(Request $request, int $id): JsonResponse
    {
        $org = Organization::find($id);

        if (!$org) {
            return $this->errorResponse('Organization not found.', 404);
        }

        $name = trim((string) $request->input('name', ''));

        if ($name === '') {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'name', 'message' => 'Name is required.', 'source' => 'JSON'],
            ]);
        }

        if (mb_strlen($name) > self::NAME_MAX_LENGTH) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'name', 'message' => 'Name may not exceed ' . self::NAME_MAX_LENGTH . ' characters.', 'source' => 'JSON'],
            ]);
        }

        if (Organization::where('name', $name)->where('id', '!=', $id)->exists()) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'name', 'message' => 'An organization with this name already exists.', 'source' => 'JSON'],
            ]);
        }

        if ($request->has('mailboxId')) {
            $mailboxId = $this->parseMailboxId($request, 'mailboxId', $mbError);
            if ($mbError) {
                return $this->errorResponse('Validation failed', 400, [
                    ['path' => 'mailboxId', 'message' => $mbError, 'source' => 'JSON'],
                ]);
            }
        } else {
            $mailboxId = $org->mailbox_id;
        }

        if ($request->has('color')) {
            $color = $this->parseColor($request, 'color', $colorError);
            if ($colorError) {
                return $this->errorResponse('Validation failed', 400, [
                    ['path' => 'color', 'message' => $colorError, 'source' => 'JSON'],
                ]);
            }
        } else {
            $color = $org->color;
        }

        $isActive = $request->has('isActive') ? (bool) $request->input('isActive') : (bool) $org->is_active;

        $org->update([
            'name'       => $name,
            'mailbox_id' => $mailboxId,
            'color'      => $color,
            'is_active'  => $isActive,
        ]);

        return response()->json(['success' => true, 'message' => 'Organization updated.']);
    }

    /**
     * DELETE /api/organizations/{id}
     * Delete an organization. Blocked when it has active members or tickets.
     */
    public function deleteOrganization(int $id): JsonResponse
    {
        $org = Organization::withCount(['members', 'conversations'])->find($id);

        if (!$org) {
            return $this->errorResponse('Organization not found.', 404);
        }

        if ($org->members_count > 0) {
            return $this->errorResponse(
                'Cannot delete an organization that has members. Remove all members first.',
                422,
                ['members_count' => $org->members_count]
            );
        }

        if ($org->conversations_count > 0) {
            return $this->errorResponse(
                'Cannot delete an organization that has tickets. Reassign or delete all tickets first.',
                422,
                ['conversations_count' => $org->conversations_count]
            );
        }

        $org->delete();

        return response()->json(['success' => true, 'message' => 'Organization deleted.']);
    }

    // ─── Organization members (sub-resource) ─────────────────────────────────

    /**
     * GET /api/organizations/{id}/members
     * List all members of an organization.
     */
    public function listMembers(int $id): JsonResponse
    {
        $org = Organization::find($id);

        if (!$org) {
            return $this->errorResponse('Organization not found.', 404);
        }

        return response()->json([
            '_embedded' => [
                'members' => $org->members()->orderBy('id')->get()
                    ->map(fn ($m) => $this->memberToArray($m))->values()->all(),
            ],
        ]);
    }

    /**
     * GET /api/organizations/{id}/members/{memberId}
     * Get a single member record.
     */
    public function getMember(int $id, int $memberId): JsonResponse
    {
        $org = Organization::find($id);

        if (!$org) {
            return $this->errorResponse('Organization not found.', 404);
        }

        $member = OrganizationMember::where('organization_id', $id)->where('id', $memberId)->first();

        if (!$member) {
            return $this->errorResponse('Member not found.', 404);
        }

        return response()->json($this->memberToArray($member));
    }

    /**
     * PUT /api/organizations/{id}/members/{memberId}
     * Update a member's role, unit, canManageOrg, or isActive.
     *
     * Body: { "role": "member"|"manager", "unitId": 2|null, "canManageOrg": false, "isActive": true }
     */
    public function updateMember(Request $request, int $id, int $memberId): JsonResponse
    {
        $org = Organization::find($id);

        if (!$org) {
            return $this->errorResponse('Organization not found.', 404);
        }

        $member = OrganizationMember::where('organization_id', $id)->where('id', $memberId)->first();

        if (!$member) {
            return $this->errorResponse('Member not found.', 404);
        }

        $updates = [];

        if ($request->has('role')) {
            $role = $request->input('role');
            if (!in_array($role, ['member', 'manager'], true)) {
                return $this->errorResponse('Validation failed', 400, [
                    ['path' => 'role', 'message' => 'role must be "member" or "manager".', 'source' => 'JSON'],
                ]);
            }
            $updates['role'] = $role;
        }

        if ($request->has('unitId')) {
            $unitId = $request->input('unitId') ?: null;
            if ($unitId !== null && !OrganizationUnit::where('organization_id', $id)->where('id', $unitId)->exists()) {
                return $this->errorResponse('Validation failed', 400, [
                    ['path' => 'unitId', 'message' => 'Unit does not belong to organization #' . $id . '.', 'source' => 'JSON'],
                ]);
            }
            $updates['unit_id'] = $unitId;
        }

        if ($request->has('canManageOrg')) {
            $updates['can_manage_org'] = (bool) $request->input('canManageOrg');
        }

        if ($request->has('isActive')) {
            $updates['is_active'] = (bool) $request->input('isActive');
        }

        if (empty($updates)) {
            return response()->json(['success' => true, 'message' => 'No changes.']);
        }

        $member->update($updates);

        return response()->json(['success' => true, 'message' => 'Member updated.']);
    }

    /**
     * DELETE /api/organizations/{id}/members/{memberId}
     * Remove a member from an organization.
     */
    public function deleteMember(int $id, int $memberId): JsonResponse
    {
        $org = Organization::find($id);

        if (!$org) {
            return $this->errorResponse('Organization not found.', 404);
        }

        $member = OrganizationMember::where('organization_id', $id)->where('id', $memberId)->first();

        if (!$member) {
            return $this->errorResponse('Member not found.', 404);
        }

        $ticketsCount = $this->blockingTicketsCount($member->customer_id, $id);
        if ($ticketsCount > 0) {
            return $this->errorResponse(
                'Cannot remove this member: they have tickets in this organization. Deactivate them instead (isActive: false) to preserve their ticket history.',
                422,
                ['tickets_count' => $ticketsCount]
            );
        }

        $member->delete();

        return response()->json(['success' => true, 'message' => 'Member removed.']);
    }

    // ─── Organization tags (sub-resource) ────────────────────────────────────

    /**
     * GET /api/organizations/{id}/tags
     * List all tag bindings for an organization.
     * Requires the Tags module to be active.
     */
    public function listTags(int $id): JsonResponse
    {
        if (!\Module::isActive('tags')) {
            return $this->errorResponse('Tags module is not active.', 503);
        }

        $org = Organization::find($id);

        if (!$org) {
            return $this->errorResponse('Organization not found.', 404);
        }

        return response()->json([
            '_embedded' => [
                'tags' => $org->organizationTags()->orderBy('tag_id')->get()
                    ->map(fn ($t) => $this->tagToArray($t))->values()->all(),
            ],
        ]);
    }

    /**
     * PUT /api/organizations/{id}/tags
     * Replace all tag bindings for an organization (full replace semantics).
     * Requires the Tags module to be active.
     *
     * Body: [ { "tagId": 5, "unitId": 2 }, { "tagId": 8 } ]
     */
    public function setTags(Request $request, int $id): JsonResponse
    {
        if (!\Module::isActive('tags')) {
            return $this->errorResponse('Tags module is not active.', 503);
        }

        $org = Organization::find($id);

        if (!$org) {
            return $this->errorResponse('Organization not found.', 404);
        }

        $items = $request->json()->all();

        if (!is_array($items)) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => '(root)', 'message' => 'Request body must be a JSON array.', 'source' => 'JSON'],
            ]);
        }

        $rows   = [];
        $errors = [];

        foreach ($items as $i => $item) {
            $tagId  = isset($item['tagId'])  ? (int) $item['tagId']  : null;
            $unitId = isset($item['unitId']) ? (int) $item['unitId'] : null;

            if (!$tagId || $tagId <= 0) {
                $errors[] = ['path' => "[$i].tagId", 'message' => 'tagId must be a positive integer.', 'source' => 'JSON'];
                continue;
            }

            if ($unitId !== null && !OrganizationUnit::where('organization_id', $id)->where('id', $unitId)->exists()) {
                $errors[] = ['path' => "[$i].unitId", 'message' => 'Unit does not belong to organization #' . $id . '.', 'source' => 'JSON'];
                continue;
            }

            $rows[] = [
                'organization_id' => $id,
                'tag_id'          => $tagId,
                'unit_id'         => $unitId ?: null,
            ];
        }

        if ($errors) {
            return $this->errorResponse('Validation failed', 400, $errors);
        }

        \DB::transaction(function () use ($id, $rows) {
            OrganizationTag::where('organization_id', $id)->delete();
            foreach ($rows as $row) {
                OrganizationTag::create($row);
            }
        });

        return response()->json(['success' => true, 'message' => 'Tags updated.']);
    }

    // ─── Customer membership ─────────────────────────────────────────────────

    /**
     * GET /api/customers/{customerId}/organization
     * Get the organization membership for a customer.
     */
    public function getCustomerOrganization(int $customerId): JsonResponse
    {
        if (!Customer::where('id', $customerId)->exists()) {
            return $this->errorResponse('Customer not found.', 404);
        }

        $member = OrganizationMember::where('customer_id', $customerId)
            ->where('is_active', true)
            ->with('organization')
            ->first();

        if (!$member) {
            return $this->errorResponse('Customer is not a member of any organization.', 404);
        }

        return response()->json([
            'customerId'         => $member->customer_id,
            'organizationId'     => $member->organization_id,
            'organizationName'   => optional($member->organization)->name,
            'unitId'             => $member->unit_id,
            'unitName'           => optional($member->unit)->name,
            'role'               => $member->role,
            'canManageOrg'       => (bool) $member->can_manage_org,
            'isActive'           => (bool) $member->is_active,
            'notifyOnNewTicket'  => (bool) $member->notify_on_new_ticket,
        ]);
    }

    /**
     * PUT /api/customers/{customerId}/organization
     * Assign or update a customer's organization membership.
     *
     * Body: { "organizationId": 1, "role": "member"|"manager", "unitId": 2|null,
     *         "canManageOrg": false, "isActive": true }
     */
    public function setCustomerOrganization(Request $request, int $customerId): JsonResponse
    {
        if (!Customer::where('id', $customerId)->exists()) {
            return $this->errorResponse('Customer not found.', 404);
        }

        $orgId = $request->input('organizationId');

        if (!$orgId) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'organizationId', 'message' => 'organizationId is required.', 'source' => 'JSON'],
            ]);
        }

        if ($request->has('role') && !in_array($request->input('role'), ['member', 'manager'], true)) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'role', 'message' => 'role must be "member" or "manager".', 'source' => 'JSON'],
            ]);
        }

        if (!Organization::where('id', $orgId)->exists()) {
            return $this->errorResponse('Organization not found.', 404);
        }

        // Unit (if provided) must belong to the target organization.
        $unitId = $request->has('unitId') ? ($request->input('unitId') ?: null) : null;
        if ($unitId && !OrganizationUnit::where('organization_id', $orgId)->where('id', $unitId)->exists()) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'unitId', 'message' => 'Unit does not belong to organization #' . $orgId . '.', 'source' => 'JSON'],
            ]);
        }

        $member = OrganizationMember::where('customer_id', $customerId)
            ->where('organization_id', $orgId)
            ->first();

        // One ACTIVE membership per customer. This applies both to a brand-new
        // membership and to re-activating a dormant one — without the second
        // check, isActive: true here would silently give the customer two
        // active memberships at once.
        $wouldBeActive = $member
            ? ($request->has('isActive') ? (bool) $request->input('isActive') : (bool) $member->is_active)
            : ($request->has('isActive') ? (bool) $request->input('isActive') : true);

        if ($wouldBeActive) {
            $activeElsewhere = OrganizationMember::where('customer_id', $customerId)
                ->where('organization_id', '!=', $orgId)
                ->where('is_active', true)
                ->first();

            if ($activeElsewhere) {
                return $this->errorResponse('Customer already has an active membership in another organization.', 409, [
                    [
                        'path'    => 'organizationId',
                        'message' => 'Customer is an active member of organization #' . $activeElsewhere->organization_id
                                     . '. Deactivate or remove it first via DELETE /api/customers/' . $customerId . '/organization.',
                        'source'  => 'JSON',
                    ],
                ]);
            }
        }

        if (!$member) {
            OrganizationMember::create([
                'organization_id' => $orgId,
                'customer_id'     => $customerId,
                'unit_id'         => $unitId,
                'role'            => $request->input('role', 'member'),
                'can_manage_org'  => (bool) $request->input('canManageOrg', false),
                'is_active'       => $request->has('isActive') ? (bool) $request->input('isActive') : true,
            ]);
            return response()->json(['success' => true, 'message' => 'Membership created.'], 201);
        }

        // Partial update: only fields actually present in the body are touched,
        // matching PUT /organizations/{id}/members/{memberId}. Previously any
        // omitted field was reset to its default, which silently demoted
        // managers, cleared units and re-activated deactivated members.
        $updates = [];

        if ($request->has('unitId')) {
            $updates['unit_id'] = $unitId;
        }

        if ($request->has('role')) {
            $updates['role'] = $request->input('role');
        }

        if ($request->has('canManageOrg')) {
            $updates['can_manage_org'] = (bool) $request->input('canManageOrg');
        }

        if ($request->has('isActive')) {
            $updates['is_active'] = (bool) $request->input('isActive');
        }

        if (empty($updates)) {
            return response()->json(['success' => true, 'message' => 'No changes.']);
        }

        $member->update($updates);

        return response()->json(['success' => true, 'message' => 'Membership updated.']);
    }

    // ─── Units ───────────────────────────────────────────────────────────────

    /**
     * GET /api/organizations/{id}/units
     */
    public function listUnits(int $id): JsonResponse
    {
        $org = Organization::find($id);

        if (!$org) {
            return $this->errorResponse('Organization not found.', 404);
        }

        return response()->json([
            '_embedded' => [
                'units' => $org->units()->orderBy('name')->get()
                    ->map(fn ($u) => $this->unitToArray($u))->values()->all(),
            ],
        ]);
    }

    /**
     * POST /api/organizations/{id}/units
     * Body: { "name": "Sales department" }
     */
    public function createUnit(Request $request, int $id): JsonResponse
    {
        $org = Organization::find($id);

        if (!$org) {
            return $this->errorResponse('Organization not found.', 404);
        }

        $name = trim((string) $request->input('name', ''));

        if ($name === '') {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'name', 'message' => 'Name is required.', 'source' => 'JSON'],
            ]);
        }

        if (mb_strlen($name) > self::NAME_MAX_LENGTH) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'name', 'message' => 'Name may not exceed ' . self::NAME_MAX_LENGTH . ' characters.', 'source' => 'JSON'],
            ]);
        }

        if (OrganizationUnit::where('organization_id', $id)->where('name', $name)->exists()) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'name', 'message' => 'A unit with this name already exists in this organization.', 'source' => 'JSON'],
            ]);
        }

        $unit = OrganizationUnit::create(['organization_id' => $id, 'name' => $name]);

        return response()->json($this->unitToArray($unit), 201)
            ->header('Resource-ID', $unit->id);
    }

    /**
     * PUT /api/units/{unitId}
     * Body: { "name": "New name" }
     */
    public function updateUnit(Request $request, int $unitId): JsonResponse
    {
        $unit = OrganizationUnit::find($unitId);

        if (!$unit) {
            return $this->errorResponse('Unit not found.', 404);
        }

        $name = trim((string) $request->input('name', ''));

        if ($name === '') {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'name', 'message' => 'Name is required.', 'source' => 'JSON'],
            ]);
        }

        if (mb_strlen($name) > self::NAME_MAX_LENGTH) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'name', 'message' => 'Name may not exceed ' . self::NAME_MAX_LENGTH . ' characters.', 'source' => 'JSON'],
            ]);
        }

        if (OrganizationUnit::where('organization_id', $unit->organization_id)
            ->where('name', $name)->where('id', '!=', $unit->id)->exists()
        ) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'name', 'message' => 'A unit with this name already exists in this organization.', 'source' => 'JSON'],
            ]);
        }

        $unit->update(['name' => $name]);

        return response()->json(['success' => true, 'message' => 'Unit updated.']);
    }

    /**
     * DELETE /api/units/{unitId}
     * Demotes the unit's managers to members, then deletes (members are
     * unassigned via the FK).
     */
    public function deleteUnit(int $unitId): JsonResponse
    {
        $unit = OrganizationUnit::find($unitId);

        if (!$unit) {
            return $this->errorResponse('Unit not found.', 404);
        }

        OrganizationMember::where('unit_id', $unit->id)
            ->where('role', 'manager')
            ->update(['role' => 'member']);

        $unit->delete();

        return response()->json(['success' => true, 'message' => 'Unit deleted.']);
    }

    /**
     * DELETE /api/customers/{customerId}/organization
     * Remove a customer from their current (active) organization. Historical
     * (deactivated) memberships in other organizations are left untouched —
     * they are preserved the same way the admin UI preserves them.
     */
    public function removeCustomerOrganization(int $customerId): JsonResponse
    {
        if (!Customer::where('id', $customerId)->exists()) {
            return $this->errorResponse('Customer not found.', 404);
        }

        $member = OrganizationMember::where('customer_id', $customerId)
            ->where('is_active', true)
            ->first();

        if (!$member) {
            return $this->errorResponse('Customer is not an active member of any organization.', 404);
        }

        $ticketsCount = $this->blockingTicketsCount($customerId, $member->organization_id);
        if ($ticketsCount > 0) {
            return $this->errorResponse(
                'Cannot remove this membership: the customer has tickets in this organization. Deactivate instead (isActive: false) to preserve their ticket history.',
                422,
                ['tickets_count' => $ticketsCount]
            );
        }

        $member->delete();

        return response()->json(['success' => true, 'message' => 'Membership removed.']);
    }
}

<?php

namespace Modules\OrgPortal\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\OrgPortal\Models\Organization;
use Modules\OrgPortal\Models\OrganizationMember;

class OrgPortalApiController extends Controller
{
    // ─── Helpers ─────────────────────────────────────────────────────────────

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
            'mailboxId'  => $org->mailbox_id,
            'createdAt'  => $org->created_at->toIso8601String(),
            'updatedAt'  => $org->updated_at->toIso8601String(),
        ];

        if ($withMembers) {
            $data['_embedded']['members'] = $org->members->map(fn ($m) => $this->memberToArray($m))->values()->all();
        }

        return $data;
    }

    private function memberToArray(OrganizationMember $m): array
    {
        return [
            'id'                 => $m->id,
            'organizationId'     => $m->organization_id,
            'customerId'         => $m->customer_id,
            'role'               => $m->role,
            'notifyOnNewTicket'  => (bool) $m->notify_on_new_ticket,
            'createdAt'          => $m->created_at->toIso8601String(),
            'updatedAt'          => $m->updated_at->toIso8601String(),
        ];
    }

    // ─── Organizations ───────────────────────────────────────────────────────

    /**
     * GET /api/organizations
     * List all organizations (paginated).
     */
    public function listOrganizations(Request $request): JsonResponse
    {
        $perPage = min((int) $request->input('pageSize', 25), 100);
        $page    = max((int) $request->input('page', 1), 1);

        $query = Organization::orderBy('name');

        if ($request->filled('mailboxId')) {
            $mailboxId = (int) $request->input('mailboxId');
            $query->visibleInMailbox($mailboxId);
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

        if (mb_strlen($name) > 255) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'name', 'message' => 'Name may not exceed 255 characters.', 'source' => 'JSON'],
            ]);
        }

        if (Organization::where('name', $name)->exists()) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'name', 'message' => 'An organization with this name already exists.', 'source' => 'JSON'],
            ]);
        }

        $mailboxId = $request->filled('mailboxId') ? (int) $request->input('mailboxId') : null;
        if ($mailboxId && !\App\Mailbox::where('id', $mailboxId)->exists()) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'mailboxId', 'message' => 'Mailbox not found.', 'source' => 'JSON'],
            ]);
        }

        $org = Organization::create(['name' => $name, 'mailbox_id' => $mailboxId]);

        return response()->json($this->orgToArray($org), 201)
            ->header('Resource-ID', $org->id);
    }

    /**
     * GET /api/organizations/{id}
     * Get a single organization including its members.
     */
    public function getOrganization(int $id): JsonResponse
    {
        $org = Organization::with('members')->find($id);

        if (!$org) {
            return $this->errorResponse('Organization not found.', 404);
        }

        return response()->json($this->orgToArray($org, true));
    }

    /**
     * PUT /api/organizations/{id}
     * Update organization name.
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

        if (Organization::where('name', $name)->where('id', '!=', $id)->exists()) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'name', 'message' => 'An organization with this name already exists.', 'source' => 'JSON'],
            ]);
        }

        $mailboxId = $request->has('mailboxId')
            ? ($request->input('mailboxId') ? (int) $request->input('mailboxId') : null)
            : $org->mailbox_id;

        if ($request->has('mailboxId') && $mailboxId && !\App\Mailbox::where('id', $mailboxId)->exists()) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'mailboxId', 'message' => 'Mailbox not found.', 'source' => 'JSON'],
            ]);
        }

        if ($org->name === $name && $org->mailbox_id === $mailboxId) {
            return response()->json(['success' => true, 'message' => 'No changes — organization already has this name and mailbox.']);
        }

        $org->update(['name' => $name, 'mailbox_id' => $mailboxId]);

        return response()->json(['success' => true, 'message' => 'Organization updated.']);
    }

    /**
     * DELETE /api/organizations/{id}
     * Delete an organization (cascades members).
     */
    public function deleteOrganization(int $id): JsonResponse
    {
        $org = Organization::find($id);

        if (!$org) {
            return $this->errorResponse('Organization not found.', 404);
        }

        $org->delete();

        return response()->json(['success' => true, 'message' => 'Organization deleted.']);
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
            ->with('organization')
            ->first();

        if (!$member) {
            return $this->errorResponse('Customer is not a member of any organization.', 404);
        }

        return response()->json([
            'customerId'         => $member->customer_id,
            'organizationId'     => $member->organization_id,
            'organizationName'   => optional($member->organization)->name,
            'role'               => $member->role,
            'notifyOnNewTicket'  => (bool) $member->notify_on_new_ticket,
        ]);
    }

    /**
     * PUT /api/customers/{customerId}/organization
     * Assign or update a customer's organization membership.
     *
     * Body: { "organizationId": 1, "role": "member"|"manager" }
     */
    public function setCustomerOrganization(Request $request, int $customerId): JsonResponse
    {
        if (!Customer::where('id', $customerId)->exists()) {
            return $this->errorResponse('Customer not found.', 404);
        }

        $orgId = $request->input('organizationId');
        $role  = $request->input('role', 'member');

        if (!$orgId) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'organizationId', 'message' => 'organizationId is required.', 'source' => 'JSON'],
            ]);
        }

        if (!in_array($role, ['member', 'manager'])) {
            return $this->errorResponse('Validation failed', 400, [
                ['path' => 'role', 'message' => 'role must be "member" or "manager".', 'source' => 'JSON'],
            ]);
        }

        if (!Organization::where('id', $orgId)->exists()) {
            return $this->errorResponse('Organization not found.', 404);
        }

        $member = OrganizationMember::where('customer_id', $customerId)->first();

        if (!$member) {
            OrganizationMember::create([
                'organization_id' => $orgId,
                'customer_id'     => $customerId,
                'role'            => $role,
            ]);
            return response()->json(['success' => true, 'message' => 'Membership created.'], 201);
        }

        if ((int)$member->organization_id === (int)$orgId && $member->role === $role) {
            return response()->json(['success' => true, 'message' => 'No changes — customer is already a member of this organization with this role.']);
        }

        $member->organization_id = $orgId;
        $member->role            = $role;
        $member->save();

        return response()->json(['success' => true, 'message' => 'Membership updated.']);
    }

    /**
     * DELETE /api/customers/{customerId}/organization
     * Remove a customer from their organization.
     */
    public function removeCustomerOrganization(int $customerId): JsonResponse
    {
        if (!Customer::where('id', $customerId)->exists()) {
            return $this->errorResponse('Customer not found.', 404);
        }

        $deleted = OrganizationMember::where('customer_id', $customerId)->delete();

        if (!$deleted) {
            return $this->errorResponse('Customer is not a member of any organization.', 404);
        }

        return response()->json(['success' => true, 'message' => 'Membership removed.']);
    }
}

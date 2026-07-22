<?php

namespace Modules\OrgPortal\Services;

use Illuminate\Database\QueryException;
use Modules\OrgPortal\Models\OrganizationMember;
use Modules\OrgPortal\Models\OrganizationUnit;

/**
 * Single entry point for creating memberships programmatically.
 *
 * The four hand-written creation paths that predate this class (admin org form,
 * customer.updated hook, API setCustomerOrganization, API updateMember) each
 * reimplement the same guards slightly differently. Domain matching does not
 * add a fifth: it goes through here.
 *
 * Two invariants this class exists to hold:
 *
 *  1. A customer has at most ONE active membership. Nothing in the schema
 *     enforces it.
 *  2. organization_members carries unique(organization_id, customer_id) which
 *     ignores is_active — so re-adding a previously deactivated member must
 *     reactivate the existing row, never INSERT.
 */
class MembershipService
{
    /** Membership created by a human. */
    const SOURCE_MANUAL = 'manual';

    /** Membership created automatically by email-domain matching. */
    const SOURCE_DOMAIN = 'domain';

    /**
     * Add a customer to an organisation, or reactivate their existing row.
     *
     * Returns:
     *   ['status' => 'created'|'reactivated', 'member' => OrganizationMember]
     *   ['status' => 'already_in_org'|'already_member'|'unit_not_found'|'race', 'member' => null]
     *
     * 'already_in_org' — active somewhere else; the caller decides whether that
     * is an error (manual add) or a no-op (automation, where manual wins).
     */
    public static function addOrActivate(
        int $organizationId,
        int $customerId,
        ?int $unitId = null,
        string $role = 'member',
        string $source = self::SOURCE_MANUAL,
        array $attributes = []
    ): array {
        $fail = function (string $status) {
            return ['status' => $status, 'member' => null];
        };

        if ($unitId && !OrganizationUnit::where('organization_id', $organizationId)
            ->where('id', $unitId)->exists()
        ) {
            return $fail('unit_not_found');
        }

        try {
            $result = \DB::transaction(function () use (
                $organizationId, $customerId, $unitId, $role, $source, $attributes, $fail
            ) {
                $activeElsewhere = OrganizationMember::where('customer_id', $customerId)
                    ->where('is_active', true)
                    ->where('organization_id', '!=', $organizationId)
                    ->exists();

                if ($activeElsewhere) {
                    return $fail('already_in_org');
                }

                $existing = OrganizationMember::where('organization_id', $organizationId)
                    ->where('customer_id', $customerId)
                    ->first();

                if ($existing) {
                    if ($existing->is_active) {
                        return $fail('already_member');
                    }

                    // A deactivated row is a revocation, and automation must not
                    // undo it. Otherwise an admin disables a customer, that
                    // customer sends one more email, and domain matching quietly
                    // restores their portal access — the same lever also
                    // resurrects everyone that removeDomain(deactivate) just
                    // switched off. Only a human re-adds a revoked member.
                    if ($source === self::SOURCE_DOMAIN) {
                        return $fail('deactivated');
                    }

                    // Reactivate rather than insert: unique(organization_id,
                    // customer_id) ignores is_active, so a create() here fails.
                    $existing->fill($attributes + [
                        'unit_id'        => $unitId,
                        'role'           => $role,
                        'is_active'      => true,
                        'deactivated_at' => null,
                    ]);

                    // Never downgrade a human-made membership to 'domain':
                    // source is what tells a later "remove domain + deactivate
                    // its members" sweep whom it is allowed to touch.
                    if ($existing->source !== self::SOURCE_MANUAL) {
                        $existing->source = $source;
                    }

                    $existing->save();

                    return ['status' => 'reactivated', 'member' => $existing];
                }

                $member = OrganizationMember::create($attributes + [
                    'organization_id' => $organizationId,
                    'customer_id'     => $customerId,
                    'unit_id'         => $unitId,
                    'role'            => $role,
                    'source'          => $source,
                    'is_active'       => true,
                ]);

                return ['status' => 'created', 'member' => $member];
            });
        } catch (QueryException $e) {
            // Two requests adding the same customer concurrently: the unique
            // index is the real arbiter and one of them loses. Report it rather
            // than surfacing a raw SQL error to the admin.
            if (static::isDuplicateKey($e)) {
                return $fail('race');
            }
            throw $e;
        }

        if (in_array($result['status'], ['created', 'reactivated'], true)) {
            // Stamp the org snapshot onto conversations this customer already has.
            OrgAttribution::reattributeForCustomer($customerId, $organizationId, $unitId);
        }

        return $result;
    }

    /**
     * Add a customer by email-domain match. Never overrides a human decision:
     * a customer already active in any organisation is left alone.
     *
     * Returns true only when a membership was actually created or reactivated.
     */
    public static function addByDomain(int $organizationId, int $customerId, ?int $unitId = null): bool
    {
        $result = static::addOrActivate(
            $organizationId,
            $customerId,
            $unitId,
            'member',
            self::SOURCE_DOMAIN,
            [
                // Automation grants the minimum. Elevated permissions and
                // notification opt-in are deliberate acts, not side effects of
                // an email arriving.
                'can_manage_org'       => false,
                'can_view_stats'       => false,
                'notify_on_new_ticket' => false,
            ]
        );

        return in_array($result['status'], ['created', 'reactivated'], true);
    }

    /**
     * Is this customer currently an active member of this organisation?
     */
    public static function isActiveMember(int $organizationId, int $customerId): bool
    {
        return OrganizationMember::where('organization_id', $organizationId)
            ->where('customer_id', $customerId)
            ->where('is_active', true)
            ->exists();
    }

    protected static function isDuplicateKey(QueryException $e): bool
    {
        return in_array((string) ($e->errorInfo[1] ?? ''), ['1062'], true)
            || str_contains(strtolower($e->getMessage()), 'duplicate entry')
            || str_contains(strtolower($e->getMessage()), 'unique constraint');
    }
}

<?php

namespace Modules\OrgPortal\Services;

use App\Conversation;
use Modules\OrgPortal\Models\OrganizationMember;

class OrgAttribution
{
    /**
     * Stamp org_id / org_unit_id onto a single conversation from the author's
     * active membership. Called at conversation creation (write-path).
     */
    public static function attribute(Conversation $conversation): void
    {
        if (!$conversation->customer_id) return;

        $member = OrganizationMember::where('customer_id', $conversation->customer_id)
            ->where('is_active', true)
            ->first();

        Conversation::where('id', $conversation->id)->update([
            'org_id'            => $member?->organization_id,
            'org_unit_id'       => $member?->unit_id,
            'org_attributed_at' => now(),
        ]);
    }

    /**
     * Re-attribute all previously un-attributed conversations for a customer.
     * Called when a customer is added to an organisation (addMember).
     * Only touches conversations where org_attributed_at IS NULL — conversations
     * that already have a snapshot keep their original attribution.
     */
    public static function reattributeForCustomer(int $customerId, int $organizationId, ?int $unitId): void
    {
        Conversation::where('customer_id', $customerId)
            ->whereNull('org_attributed_at')
            ->update([
                'org_id'            => $organizationId,
                'org_unit_id'       => $unitId,
                'org_attributed_at' => now(),
            ]);
    }

    /**
     * Backfill one batch of un-attributed conversations.
     * Returns the number of rows processed (0 = done).
     */
    public static function backfillBatch(int $limit = 1000): int
    {
        $batch = Conversation::whereNull('org_attributed_at')
            ->whereNotNull('customer_id')
            ->orderBy('id')
            ->limit($limit)
            ->get(['id', 'customer_id']);

        if ($batch->isEmpty()) return 0;

        // One query to resolve all customer → membership mappings for this batch.
        $members = OrganizationMember::whereIn('customer_id', $batch->pluck('customer_id'))
            ->where('is_active', true)
            ->get(['customer_id', 'organization_id', 'unit_id'])
            ->keyBy('customer_id');

        $now = now();

        foreach ($batch as $conv) {
            $m = $members->get($conv->customer_id);
            Conversation::where('id', $conv->id)->update([
                'org_id'            => $m?->organization_id,
                'org_unit_id'       => $m?->unit_id,
                // Mark as processed even when org_id is NULL — keeps the cursor moving.
                'org_attributed_at' => $now,
            ]);
        }

        return $batch->count();
    }

    /**
     * Count remaining un-attributed conversations (for monitoring).
     */
    public static function pendingCount(): int
    {
        return Conversation::whereNull('org_attributed_at')
            ->whereNotNull('customer_id')
            ->count();
    }
}

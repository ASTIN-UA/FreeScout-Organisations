<?php

namespace Modules\OrgPortal\Services;

use App\Conversation;
use Modules\OrgPortal\Models\OrganizationMember;
use Modules\OrgPortal\Models\OrganizationTag;
use Illuminate\Support\Facades\Schema;

class OrgAttribution
{
    public static function attributionSource(): string
    {
        $source = (string) \Option::get('orgportal.attribution_source', 'member');
        if (in_array($source, ['tag', 'tag_only']) && !static::tagsModuleActive()) {
            return 'member';
        }
        return $source;
    }

    public static function tagsModuleActive(): bool
    {
        return \Module::isActive('tags');
    }

    /**
     * Resolve org_id + unit_id for a conversation using tag bindings.
     * Returns ['org_id' => int|null, 'unit_id' => int|null] or null if no match.
     */
    public static function resolveByTags(int $conversationId): ?array
    {
        if (!static::tagsModuleActive()) return null;
        if (!Schema::hasTable('organization_tags') || !Schema::hasTable('conversation_tag')) return null;

        $binding = \DB::table('conversation_tag')
            ->join('organization_tags', 'organization_tags.tag_id', '=', 'conversation_tag.tag_id')
            ->where('conversation_tag.conversation_id', $conversationId)
            ->select('organization_tags.organization_id', 'organization_tags.unit_id')
            ->first();

        if (!$binding) return null;

        return [
            'org_id'     => $binding->organization_id,
            'unit_id'    => $binding->unit_id,
        ];
    }

    /**
     * Stamp org_id / org_unit_id onto a single conversation.
     * Source priority depends on attribution_source setting:
     *   member     — membership only
     *   tag        — tag first, fall back to membership
     *   tag_only   — tag only
     */
    public static function attribute(Conversation $conversation): void
    {
        if (!$conversation->customer_id) return;

        $source = static::attributionSource();
        $orgId  = null;
        $unitId = null;

        // Tag-based resolution
        if (in_array($source, ['tag', 'tag_only'])) {
            $resolved = static::resolveByTags($conversation->id);
            if ($resolved) {
                $orgId  = $resolved['org_id'];
                $unitId = $resolved['unit_id'];
            }
        }

        // Membership fallback (unless tag_only)
        if ($orgId === null && $source !== 'tag_only') {
            $member = OrganizationMember::where('customer_id', $conversation->customer_id)
                ->where('is_active', true)
                ->first();
            $orgId  = $member?->organization_id;
            $unitId = $member?->unit_id;
        }

        Conversation::where('id', $conversation->id)->update([
            'org_id'            => $orgId,
            'org_unit_id'       => $unitId,
            'org_attributed_at' => now(),
        ]);
    }

    /**
     * Re-attribute a single conversation by its tag bindings (called from tag.attached hook).
     * Only overwrites if snapshot is not yet stamped OR tag attribution is higher priority.
     */
    public static function attributeByTag(int $conversationId, int $tagId): void
    {
        if (!Schema::hasColumn('conversations', 'org_attributed_at')) return;

        $source = static::attributionSource();
        if (!in_array($source, ['tag', 'tag_only'])) return;

        $binding = OrganizationTag::where('tag_id', $tagId)->first();
        if (!$binding) return;

        Conversation::where('id', $conversationId)->update([
            'org_id'            => $binding->organization_id,
            'org_unit_id'       => $binding->unit_id,
            'org_attributed_at' => now(),
        ]);
    }

    /**
     * Re-attribute all currently org-less conversations for a customer.
     * Called when a customer is added to an organisation (addMember).
     *
     * Matches on org_id IS NULL rather than org_attributed_at IS NULL: a
     * conversation created (and attributed by the cron) before the customer
     * joined any organisation gets org_id = NULL but org_attributed_at
     * stamped — so it must still be picked up here, or it stays orphaned
     * forever. Conversations already attributed to a (different) org keep
     * their original attribution.
     */
    public static function reattributeForCustomer(int $customerId, int $organizationId, ?int $unitId): void
    {
        Conversation::where('customer_id', $customerId)
            ->whereNull('org_id')
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

        $source = static::attributionSource();
        $now    = now();

        // Build tag-binding lookup: conversation_id → {org_id, unit_id}
        $tagBindings = [];
        if (in_array($source, ['tag', 'tag_only'])
            && static::tagsModuleActive()
            && Schema::hasTable('organization_tags')
            && Schema::hasTable('conversation_tag')
        ) {
            $rows = \DB::table('conversation_tag')
                ->join('organization_tags', 'organization_tags.tag_id', '=', 'conversation_tag.tag_id')
                ->whereIn('conversation_tag.conversation_id', $batch->pluck('id'))
                ->select('conversation_tag.conversation_id', 'organization_tags.organization_id', 'organization_tags.unit_id')
                ->get();
            foreach ($rows as $row) {
                // First tag binding wins per conversation
                $tagBindings[$row->conversation_id] = $row;
            }
        }

        // Build membership lookup: customer_id → member
        $members = collect();
        if ($source !== 'tag_only') {
            $members = OrganizationMember::whereIn('customer_id', $batch->pluck('customer_id'))
                ->where('is_active', true)
                ->get(['customer_id', 'organization_id', 'unit_id'])
                ->keyBy('customer_id');
        }

        foreach ($batch as $conv) {
            $orgId  = null;
            $unitId = null;

            if (isset($tagBindings[$conv->id])) {
                $orgId  = $tagBindings[$conv->id]->organization_id;
                $unitId = $tagBindings[$conv->id]->unit_id;
            } elseif ($source !== 'tag_only') {
                $m      = $members->get($conv->customer_id);
                $orgId  = $m?->organization_id;
                $unitId = $m?->unit_id;
            }

            Conversation::where('id', $conv->id)->update([
                'org_id'            => $orgId,
                'org_unit_id'       => $unitId,
                'org_attributed_at' => $now,
            ]);
        }

        return $batch->count();
    }

    /**
     * Pre-flight stats for the backfill confirmation block.
     */
    public static function preflightStats(): array
    {
        $tagsActive = static::tagsModuleActive();
        $hasBound   = $tagsActive
            && Schema::hasTable('organization_tags')
            && Schema::hasTable('conversation_tag');

        $orgsTotal   = \DB::table('organizations')->count();
        $orgsWithTags = $hasBound
            ? \DB::table('organization_tags')->distinct()->count('organization_id')
            : 0;

        $pendingTotal = Schema::hasColumn('conversations', 'org_attributed_at')
            ? Conversation::whereNull('org_attributed_at')->whereNotNull('customer_id')->count()
            : Conversation::whereNotNull('customer_id')->count();

        $pendingByTag = 0;
        if ($hasBound && $pendingTotal > 0) {
            $pendingByTag = \DB::table('conversations')
                ->whereNull('org_attributed_at')
                ->whereNotNull('customer_id')
                ->whereExists(function ($q) {
                    $q->from('conversation_tag')
                      ->join('organization_tags', 'organization_tags.tag_id', '=', 'conversation_tag.tag_id')
                      ->whereColumn('conversation_tag.conversation_id', 'conversations.id');
                })
                ->count();
        }

        return [
            'tags_active'          => $tagsActive,
            'orgs_total'           => $orgsTotal,
            'orgs_with_tags'       => $orgsWithTags,
            'orgs_without_tags'    => $orgsTotal - $orgsWithTags,
            'pending_total'        => $pendingTotal,
            'pending_by_tag'       => $pendingByTag,
            'pending_no_tag_match' => $pendingTotal - $pendingByTag,
        ];
    }

    /**
     * Same as backfillBatch() but returns a breakdown array instead of an int.
     * Used by the manual "Run backfill" action to display a detailed summary.
     */
    public static function backfillBatchDetailed(int $limit = 2000): array
    {
        $result = ['processed' => 0, 'by_tag' => 0, 'by_member' => 0, 'unmatched' => 0];

        $batch = Conversation::whereNull('org_attributed_at')
            ->whereNotNull('customer_id')
            ->orderBy('id')
            ->limit($limit)
            ->get(['id', 'customer_id']);

        if ($batch->isEmpty()) return $result;

        $source = static::attributionSource();
        $now    = now();

        $tagBindings = [];
        if (in_array($source, ['tag', 'tag_only'])
            && static::tagsModuleActive()
            && Schema::hasTable('organization_tags')
            && Schema::hasTable('conversation_tag')
        ) {
            $rows = \DB::table('conversation_tag')
                ->join('organization_tags', 'organization_tags.tag_id', '=', 'conversation_tag.tag_id')
                ->whereIn('conversation_tag.conversation_id', $batch->pluck('id'))
                ->select('conversation_tag.conversation_id', 'organization_tags.organization_id', 'organization_tags.unit_id')
                ->get();
            foreach ($rows as $row) {
                $tagBindings[$row->conversation_id] = $row;
            }
        }

        $members = collect();
        if ($source !== 'tag_only') {
            $members = OrganizationMember::whereIn('customer_id', $batch->pluck('customer_id'))
                ->where('is_active', true)
                ->get(['customer_id', 'organization_id', 'unit_id'])
                ->keyBy('customer_id');
        }

        foreach ($batch as $conv) {
            $orgId  = null;
            $unitId = null;
            $via    = 'unmatched';

            if (isset($tagBindings[$conv->id])) {
                $orgId  = $tagBindings[$conv->id]->organization_id;
                $unitId = $tagBindings[$conv->id]->unit_id;
                $via    = 'by_tag';
            } elseif ($source !== 'tag_only') {
                $m = $members->get($conv->customer_id);
                if ($m) {
                    $orgId  = $m->organization_id;
                    $unitId = $m->unit_id;
                    $via    = 'by_member';
                }
            }

            Conversation::where('id', $conv->id)->update([
                'org_id'            => $orgId,
                'org_unit_id'       => $unitId,
                'org_attributed_at' => $now,
            ]);

            $result[$via]++;
            $result['processed']++;
        }

        return $result;
    }

    /**
     * Count remaining un-attributed conversations (for monitoring).
     */
    public static function pendingCount(): int
    {
        if (!Schema::hasColumn('conversations', 'org_attributed_at')) return 0;
        return Conversation::whereNull('org_attributed_at')
            ->whereNotNull('customer_id')
            ->count();
    }

    /**
     * Attribution progress stats for the admin system panel.
     */
    public static function stats(): array
    {
        $total = Conversation::whereNotNull('customer_id')->count();
        if (!Schema::hasColumn('conversations', 'org_attributed_at')) {
            return ['total' => $total, 'attributed' => 0, 'pending' => $total];
        }
        $attributed = Conversation::whereNotNull('customer_id')->whereNotNull('org_attributed_at')->count();
        return [
            'total'      => $total,
            'attributed' => $attributed,
            'pending'    => $total - $attributed,
        ];
    }

    public static function snapshotEnabled(): bool
    {
        return (bool) \Option::get('orgportal.snapshot_visibility', false);
    }

    /**
     * Base conversation query scoped to an organisation.
     *
     * Snapshot mode (enabled via admin toggle):
     *   org_id = $orgId  (authoritative snapshot)
     *   OR (org_attributed_at IS NULL AND customer_id IN $memberIds)  — fallback for backlog tail
     *   When $unitId is provided the first branch narrows to org_unit_id = $unitId.
     *
     * Legacy mode: whereIn('customer_id', $memberIds) — original behaviour.
     *
     * In legacy mode $unitId is ignored because $memberIds is already pre-filtered by the caller.
     */
    public static function orgConversationQuery(int $orgId, $memberIds, ?int $unitId = null): \Illuminate\Database\Eloquent\Builder
    {
        $memberArr = $memberIds instanceof \Illuminate\Support\Collection
            ? $memberIds->toArray()
            : (array) $memberIds;

        if (!static::snapshotEnabled()) {
            return Conversation::whereIn('customer_id', $memberArr);
        }

        return Conversation::where(function ($q) use ($orgId, $unitId, $memberArr) {
            if ($unitId) {
                $q->where('org_unit_id', $unitId);
            } else {
                $q->where('org_id', $orgId);
            }
            $q->orWhere(function ($sq) use ($memberArr) {
                $sq->whereNull('org_attributed_at')
                   ->whereIn('customer_id', $memberArr);
            });
        });
    }
}

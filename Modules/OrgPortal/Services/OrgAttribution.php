<?php

namespace Modules\OrgPortal\Services;

use App\Conversation;
use App\Customer;
use Modules\OrgPortal\Models\OrganizationDomain;
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
     * Resolve org_id + unit_id for a customer by their email domain, scoped to
     * the mailbox the conversation belongs to.
     *
     * When a binding matches, the customer is also enrolled as a member — the
     * point of the feature is that the organisation badge, portal access and
     * filtering all start working without anyone clicking anything. Enrolment
     * never overrides an existing active membership (see MembershipService).
     *
     * Returns ['org_id' => int, 'unit_id' => int|null] or null.
     */
    public static function resolveByDomain(int $customerId, ?int $mailboxId = null): ?array
    {
        if (!Schema::hasTable('organization_domains')) return null;

        $customer = Customer::find($customerId);
        if (!$customer) return null;

        // A customer can hold several addresses; the first one that maps to a
        // binding wins. Ordering by id keeps that deterministic run to run.
        foreach ($customer->emails()->orderBy('id')->pluck('email') as $email) {
            $match = OrganizationDomain::resolveByEmail($email, $mailboxId);
            if (!$match) continue;

            MembershipService::addByDomain($match['org_id'], $customerId, $match['unit_id']);

            // Attribute only if the customer really ended up a member. When
            // enrolment was refused — most importantly because an admin
            // deactivated them — stamping the conversation anyway would put a
            // revoked customer's ticket back on the organisation's portal.
            if (!MembershipService::isActiveMember($match['org_id'], $customerId)) {
                continue;
            }

            return $match;
        }

        return null;
    }

    /**
     * Active memberships for a batch of customers, carrying the organisation's
     * mailbox so each conversation can pick one visible in its own mailbox.
     * Returns [customer_id => [ {organization_id, unit_id, org_mailbox_id}, ... ]].
     */
    protected static function membershipsForBatch(array $customerIds): array
    {
        if (!$customerIds) return [];

        $rows = \DB::table('organization_members')
            ->join('organizations', 'organizations.id', '=', 'organization_members.organization_id')
            ->whereIn('organization_members.customer_id', $customerIds)
            ->where('organization_members.is_active', true)
            ->orderBy('organization_members.id')
            ->get([
                'organization_members.customer_id',
                'organization_members.organization_id',
                'organization_members.unit_id',
                'organizations.mailbox_id as org_mailbox_id',
            ]);

        $map = [];
        foreach ($rows as $row) {
            $map[$row->customer_id][] = $row;
        }

        return $map;
    }

    /**
     * First membership visible in the given mailbox: a global organisation, or
     * one scoped to that same mailbox.
     */
    protected static function matchMembership(array $memberships, ?int $mailboxId)
    {
        foreach ($memberships as $m) {
            if ($m->org_mailbox_id === null || (int) $m->org_mailbox_id === (int) $mailboxId) {
                return $m;
            }
        }

        return null;
    }

    /**
     * Constrain a membership query to organisations visible in a mailbox:
     * global ones (mailbox_id IS NULL) plus those scoped to that mailbox.
     *
     * Without this, a membership created by a mailbox-scoped domain binding
     * leaks everywhere — organization_members has no mailbox column, so the
     * membership lookup would happily attribute a conversation from a
     * completely different mailbox to that organisation. Mirrors
     * Organization::scopeVisibleInMailbox().
     */
    protected static function scopeMembershipToMailbox($query, ?int $mailboxId)
    {
        return $query->whereExists(function ($q) use ($mailboxId) {
            $q->selectRaw('1')
              ->from('organizations')
              ->whereColumn('organizations.id', 'organization_members.organization_id')
              ->where(function ($w) use ($mailboxId) {
                  $w->whereNull('organizations.mailbox_id');
                  if ($mailboxId) {
                      $w->orWhere('organizations.mailbox_id', $mailboxId);
                  }
              });
        });
    }

    /**
     * Ordered, normalised email domains per customer, for a whole batch.
     * Returns [customer_id => ['company.com', ...]].
     */
    protected static function domainsForCustomers(array $customerIds): array
    {
        if (!$customerIds) return [];

        $rows = \DB::table('emails')
            ->whereIn('customer_id', $customerIds)
            ->orderBy('id')
            ->get(['customer_id', 'email']);

        $map = [];
        foreach ($rows as $row) {
            $domain = OrganizationDomain::fromEmail($row->email);
            if ($domain === '' || OrganizationDomain::isPublicDomain($domain)) continue;
            $map[$row->customer_id][] = $domain;
        }

        return $map;
    }

    /**
     * Domain bindings keyed "mailbox_slot|domain", for a whole batch.
     */
    protected static function domainBindings(array $domains): array
    {
        if (!$domains || !Schema::hasTable('organization_domains')) return [];

        $rows = OrganizationDomain::whereIn('domain', array_unique($domains))
            ->get(['domain', 'mailbox_id', 'organization_id', 'unit_id']);

        $map = [];
        foreach ($rows as $row) {
            $map[$row->mailbox_id . '|' . $row->domain] = $row;
        }

        return $map;
    }

    /**
     * Pick the binding for a customer in a given mailbox, preferring the
     * mailbox-specific rule over the global one.
     */
    protected static function matchDomain(array $customerDomains, array $bindings, ?int $mailboxId): ?array
    {
        $slots = $mailboxId
            ? [$mailboxId, OrganizationDomain::GLOBAL_MAILBOX]
            : [OrganizationDomain::GLOBAL_MAILBOX];

        foreach ($customerDomains as $domain) {
            foreach ($slots as $slot) {
                $row = $bindings[$slot . '|' . $domain] ?? null;
                if ($row) {
                    return ['org_id' => (int) $row->organization_id, 'unit_id' => $row->unit_id];
                }
            }
        }

        return null;
    }

    /**
     * Stamp org_id / org_unit_id onto a single conversation.
     * Source priority depends on attribution_source setting:
     *   member     — membership only
     *   tag        — tag first, fall back to membership
     *   tag_only   — tag only
     *
     * Email-domain matching runs last in every mode except tag_only: it is the
     * fallback for customers no explicit rule covers, and must never displace
     * a tag binding or a membership an admin set by hand.
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

        // Membership fallback (unless tag_only), limited to organisations that
        // are actually visible in this conversation's mailbox.
        if ($orgId === null && $source !== 'tag_only') {
            $member = static::scopeMembershipToMailbox(
                OrganizationMember::where('customer_id', $conversation->customer_id)
                    ->where('is_active', true),
                $conversation->mailbox_id
            )->first();
            $orgId  = $member?->organization_id;
            $unitId = $member?->unit_id;
        }

        // Email-domain fallback
        if ($orgId === null && $source !== 'tag_only') {
            $resolved = static::resolveByDomain($conversation->customer_id, $conversation->mailbox_id);
            if ($resolved) {
                $orgId  = $resolved['org_id'];
                $unitId = $resolved['unit_id'];
            }
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
            ->get(['id', 'customer_id', 'mailbox_id']);

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

        // Build membership lookup: customer_id → [memberships with org mailbox]
        $members = [];
        if ($source !== 'tag_only') {
            $members = static::membershipsForBatch($batch->pluck('customer_id')->unique()->values()->all());
        }

        // Domain lookup — needed for anyone without a usable membership. Which
        // membership is usable depends on the conversation's mailbox, so this
        // cannot be narrowed to "customers with no membership at all".
        $custDomains = [];
        $bindings    = [];
        if ($source !== 'tag_only') {
            $custDomains = static::domainsForCustomers(
                $batch->pluck('customer_id')->unique()->values()->all()
            );
            $bindings    = static::domainBindings(array_merge(...array_values($custDomains) ?: [[]]));
        }

        foreach ($batch as $conv) {
            $orgId  = null;
            $unitId = null;

            if (isset($tagBindings[$conv->id])) {
                $orgId  = $tagBindings[$conv->id]->organization_id;
                $unitId = $tagBindings[$conv->id]->unit_id;
            } elseif ($source !== 'tag_only') {
                $m = static::matchMembership($members[$conv->customer_id] ?? [], $conv->mailbox_id);
                $orgId  = $m?->organization_id;
                $unitId = $m?->unit_id;

                if ($orgId === null && isset($custDomains[$conv->customer_id])) {
                    $match = static::matchDomain($custDomains[$conv->customer_id], $bindings, $conv->mailbox_id);
                    if ($match) {
                        MembershipService::addByDomain($match['org_id'], $conv->customer_id, $match['unit_id']);
                        // Only attribute if enrolment actually took (see resolveByDomain).
                        if (MembershipService::isActiveMember($match['org_id'], $conv->customer_id)) {
                            $orgId  = $match['org_id'];
                            $unitId = $match['unit_id'];
                        }
                    }
                }
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
        $result = ['processed' => 0, 'by_tag' => 0, 'by_member' => 0, 'by_domain' => 0, 'unmatched' => 0];

        $batch = Conversation::whereNull('org_attributed_at')
            ->whereNotNull('customer_id')
            ->orderBy('id')
            ->limit($limit)
            ->get(['id', 'customer_id', 'mailbox_id']);

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

        $members = [];
        if ($source !== 'tag_only') {
            $members = static::membershipsForBatch($batch->pluck('customer_id')->unique()->values()->all());
        }

        $custDomains = [];
        $bindings    = [];
        if ($source !== 'tag_only') {
            $custDomains = static::domainsForCustomers(
                $batch->pluck('customer_id')->unique()->values()->all()
            );
            $bindings    = static::domainBindings(array_merge(...array_values($custDomains) ?: [[]]));
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
                $m = static::matchMembership($members[$conv->customer_id] ?? [], $conv->mailbox_id);
                if ($m) {
                    $orgId  = $m->organization_id;
                    $unitId = $m->unit_id;
                    $via    = 'by_member';
                } elseif (isset($custDomains[$conv->customer_id])) {
                    $match = static::matchDomain($custDomains[$conv->customer_id], $bindings, $conv->mailbox_id);
                    if ($match) {
                        MembershipService::addByDomain($match['org_id'], $conv->customer_id, $match['unit_id']);

                        // Only attribute if enrolment actually took (see resolveByDomain).
                        if (MembershipService::isActiveMember($match['org_id'], $conv->customer_id)) {
                            $orgId  = $match['org_id'];
                            $unitId = $match['unit_id'];
                            $via    = 'by_domain';
                        }
                    }
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

<?php

namespace Modules\OrgPortal\Models;

use Illuminate\Database\Eloquent\Model;

class OrgNotificationSubscription extends Model
{
    protected $table = 'org_notification_subscriptions';

    protected $fillable = ['member_id', 'event', 'scope_type', 'scope_id'];

    const EVENT_NEW_TICKET     = 'new_ticket';
    const EVENT_REPLY_AGENT    = 'reply_agent';
    const EVENT_REPLY_CUSTOMER = 'reply_customer';

    const SCOPE_ORG  = 'org';
    const SCOPE_UNIT = 'unit';

    public function member()
    {
        return $this->belongsTo(OrganizationMember::class, 'member_id');
    }

    /**
     * Check whether a given member has a subscription that covers the author's context.
     * A subscriber is notified if they have an 'org' subscription OR a 'unit' subscription
     * matching the author's unit_id.
     */
    public static function memberIsSubscribed(int $subscriberMemberId, string $event, $authorUnitId): bool
    {
        return self::where('member_id', $subscriberMemberId)
            ->where('event', $event)
            ->where(function ($q) use ($authorUnitId) {
                $q->where('scope_type', self::SCOPE_ORG)
                  ->orWhere(function ($q2) use ($authorUnitId) {
                      $q2->where('scope_type', self::SCOPE_UNIT);
                      if (is_null($authorUnitId)) {
                          $q2->whereNull('scope_id');
                      } else {
                          $q2->where('scope_id', $authorUnitId);
                      }
                  });
            })
            ->exists();
    }
}

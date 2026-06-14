<?php

namespace Modules\OrgPortal\Models;

use Illuminate\Database\Eloquent\Model;

class OrgPortalThreadView extends Model
{
    public $timestamps = false;

    protected $table = 'org_portal_thread_views';

    protected $fillable = ['thread_id', 'conversation_id', 'customer_id', 'viewed_at'];

    protected $dates = ['viewed_at'];

    public function customer()
    {
        return $this->belongsTo(\App\Customer::class);
    }

    /**
     * Record that a manager viewed all unviewed threads of a conversation.
     */
    public static function markConversationViewed(int $conversationId, int $customerId): void
    {
        $now = now();

        $alreadyViewed = static::where('conversation_id', $conversationId)
            ->where('customer_id', $customerId)
            ->pluck('thread_id')
            ->all();

        $threadIds = \App\Thread::where('conversation_id', $conversationId)
            ->whereIn('type', [\App\Thread::TYPE_CUSTOMER, \App\Thread::TYPE_MESSAGE])
            ->whereNotIn('id', $alreadyViewed)
            ->pluck('id');

        if ($threadIds->isEmpty()) return;

        $rows = $threadIds->map(fn($id) => [
            'thread_id'       => $id,
            'conversation_id' => $conversationId,
            'customer_id'     => $customerId,
            'viewed_at'       => $now,
        ])->all();

        static::insert($rows);
    }

    public function member()
    {
        return $this->hasOne(
            \Modules\OrgPortal\Models\OrganizationMember::class,
            'customer_id',
            'customer_id'
        )->where('is_active', true);
    }

    /**
     * All view records for a given thread, with customer and org member eager-loaded.
     */
    public static function forThread(int $threadId)
    {
        return static::where('thread_id', $threadId)
            ->with(['customer', 'member'])
            ->get();
    }
}

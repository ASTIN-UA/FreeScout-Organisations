<?php

namespace Modules\OrgPortal\Models;

use Illuminate\Database\Eloquent\Model;

class OrgPortalNotification extends Model
{
    const TYPE_NEW_TICKET      = 'new_ticket';
    const TYPE_NEW_REPLY       = 'new_reply';
    const TYPE_CUSTOMER_REPLY  = 'customer_reply';

    public $timestamps = false;

    protected $table = 'org_portal_notifications';

    protected $fillable = [
        'customer_id',
        'conversation_id',
        'thread_id',
        'type',
        'read_at',
        'created_at',
    ];

    protected $dates = ['read_at', 'created_at'];

    public function conversation()
    {
        return $this->belongsTo(\App\Conversation::class);
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Unread notifications for a customer, newest first, limit 20.
     */
    public static function unreadFor(int $customerId)
    {
        return static::where('customer_id', $customerId)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
    }

    public static function unreadCountFor(int $customerId): int
    {
        return static::where('customer_id', $customerId)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Mark all notifications for a customer+conversation as read (only for ticket author).
     */
    public static function markReadForConversation(int $customerId, int $conversationId): void
    {
        static::where('customer_id', $customerId)
            ->where('conversation_id', $conversationId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Create a notification, avoiding duplicates for same customer+conversation+type
     * within the last minute (prevents double-fire from multiple event sources).
     */
    public static function createIfNotDuplicate(
        int $customerId,
        int $conversationId,
        ?int $threadId,
        string $type
    ): void {
        $recentDuplicate = static::where('customer_id', $customerId)
            ->where('conversation_id', $conversationId)
            ->where('type', $type)
            ->where('created_at', '>=', now()->subMinute())
            ->exists();

        if ($recentDuplicate) {
            return;
        }

        static::create([
            'customer_id'     => $customerId,
            'conversation_id' => $conversationId,
            'thread_id'       => $threadId,
            'type'            => $type,
            'created_at'      => now(),
        ]);
    }
}

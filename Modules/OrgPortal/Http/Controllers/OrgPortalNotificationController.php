<?php

namespace Modules\OrgPortal\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\OrgPortal\Models\OrgPortalNotification;

class OrgPortalNotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $customer = \EndUserPortal::authCustomer();
        if (!$customer) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $notifications = OrgPortalNotification::unreadFor($customer->id);

        $today     = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        $items = $notifications->map(function ($n) use ($today, $yesterday) {
            $conv = $n->conversation;
            if (!$conv) return null;

            // Actor name: the one who triggered the notification
            $actorName = '';
            if ($n->thread_id) {
                $thread = \App\Thread::find($n->thread_id);
                if ($thread) {
                    if ($thread->created_by_user_id) {
                        $user = \App\User::find($thread->created_by_user_id);
                        $actorName = $user ? $user->getFullName() : '';
                    } elseif ($thread->created_by_customer_id) {
                        $cust = \App\Customer::find($thread->created_by_customer_id);
                        $actorName = $cust ? $cust->getFullName() : '';
                    }
                    // Plain text preview (strip tags, limit 80 chars)
                    $preview = strip_tags($thread->body ?? '');
                    $preview = mb_strlen($preview) > 80 ? mb_substr($preview, 0, 80) . '…' : $preview;
                } else {
                    $preview = '';
                }
            } else {
                $preview = strip_tags($conv->subject ?? '');
            }

            // Date group label
            $dateStr = $n->created_at ? $n->created_at->toDateString() : $today;
            if ($dateStr === $today) {
                $dateGroup = 'today';
            } elseif ($dateStr === $yesterday) {
                $dateGroup = 'yesterday';
            } else {
                $dateGroup = $n->created_at ? $n->created_at->format('d.m.Y') : '';
            }

            return [
                'id'              => $n->id,
                'type'            => $n->type,
                'conversation_id' => $n->conversation_id,
                'subject'         => e($conv->subject ?? ''),
                'number'          => $conv->number ?? '',
                'actor_name'      => e($actorName),
                'preview'         => e($preview),
                'date_group'      => $dateGroup,
                'time'            => $n->created_at ? $n->created_at->format('H:i') : '',
                'url'             => route('orgportal.portal.ticket', [
                    'mailbox_id'      => \EndUserPortal::encodeMailboxId($conv->mailbox_id),
                    'conversation_id' => $n->conversation_id,
                ]),
            ];
        })->filter()->values();

        return response()->json([
            'count' => $items->count(),
            'items' => $items,
        ]);
    }

    public function markRead(Request $request, int $conversationId): JsonResponse
    {
        $customer = \EndUserPortal::authCustomer();
        if (!$customer) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Mark this customer's own notifications for the conversation as read.
        // Works for both the ticket author and managers who received the notification.
        OrgPortalNotification::markReadForConversation($customer->id, $conversationId);

        return response()->json(['ok' => true]);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $customer = \EndUserPortal::authCustomer();
        if (!$customer) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        OrgPortalNotification::where('customer_id', $customer->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}

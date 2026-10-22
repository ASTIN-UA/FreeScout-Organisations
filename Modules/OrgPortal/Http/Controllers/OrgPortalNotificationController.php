<?php

namespace Modules\OrgPortal\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\OrgPortal\Models\OrganizationMember;
use Modules\OrgPortal\Models\OrgPortalNotification;
use Modules\OrgPortal\Services\OrgAttribution;

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

        $items = $notifications->map(function ($n) use ($today, $yesterday, $customer) {
            $conv = $n->conversation;
            if (!$conv) return null;

            // The page this customer can actually open: the organization's ticket
            // page when he manages the ticket's scope; his own ticket otherwise
            // opens on the portal's own page (an ordinary customer has no
            // organization pages — the bell used to send him to «Access denied»).
            // Nothing he can open any more — not listed.
            if ($this->managerCanOpen($customer, $conv)) {
                $url = route('orgportal.portal.ticket', [
                    'mailbox_id'      => \EndUserPortal::encodeMailboxId($conv->mailbox_id),
                    'conversation_id' => $n->conversation_id,
                ]);
            } elseif ((int) $conv->customer_id === (int) $customer->id) {
                $url = route('enduserportal.ticket', [
                    'mailbox_id'      => \EndUserPortal::encodeMailboxId($conv->mailbox_id),
                    'conversation_id' => $conv->id,
                ]);
            } else {
                return null;
            }

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
                'url'             => $url,
            ];
        })->filter()->values();

        return response()->json([
            'count' => $items->count(),
            'items' => $items,
        ]);
    }

    // The route is help/{mailbox_id}/org/notifications/{conversation_id}/read: both
    // parameters are passed in order, so the mailbox comes first. With only one
    // parameter here the encoded mailbox id was taken for the conversation and no
    // notification was ever marked read from the bell.
    public function markRead(Request $request, string $mailbox_id, int $conversationId): JsonResponse
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

    /**
     * Whether the organization's ticket page opens $conversation for $customer —
     * the same rule as that page (OrgPortalFrontController::requireManager(),
     * visibleCustomerIds(), scopeUnitId()): an active manager of an active
     * organization of this mailbox; a global manager — the whole organization,
     * a unit manager — his unit.
     */
    protected function managerCanOpen($customer, $conversation): bool
    {
        $member = OrganizationMember::where('customer_id', $customer->id)
            ->where('role', 'manager')
            ->where('is_active', true)
            ->with('organization')
            ->first();

        if (!$member || !$member->organization || !$member->organization->is_active) {
            return false;
        }

        $orgMailboxId = $member->organization->mailbox_id;
        if ($orgMailboxId !== null && (int) $orgMailboxId !== (int) $conversation->mailbox_id) {
            return false;
        }

        $ids = OrganizationMember::where('organization_id', $member->organization_id);
        if ($member->isUnitManager()) {
            $ids->where('unit_id', $member->unit_id);
        }

        return OrgAttribution::orgConversationQuery(
            $member->organization_id,
            $ids->pluck('customer_id'),
            $member->isUnitManager() ? (int) $member->unit_id : null
        )
            ->where('mailbox_id', $conversation->mailbox_id)
            ->whereKey($conversation->id)
            ->exists();
    }
}

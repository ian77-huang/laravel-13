<?php

namespace App\Http\Controllers\Api;

use App\Enums\FriendshipStatus;
use App\Events\NotificationCreated;
use App\Http\Controllers\Controller;
use App\Models\Friendships;
use App\Models\Notifications;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FriendshipsController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        return response()->json([
            'users' => User::withFriendStatus($request->user()->id)
                ->get()
                ->toArray(),
        ]);
    }

    /**
     * Send a friend request.
     */
    public function sendRequest(Request $request, User $friend): JsonResponse
    {
        $me = $request->user();

        abort_if($friend->is_admin || $friend->is_active === false, 404);

        if ($friend->id === $me->id) {
            return $this->fail(__('user.friendships.error.self'), 422);
        }

        $existing = $this->findBetween($me->id, $friend->id);

        if ($existing === null) {
            Friendships::create([
                'user_id' => $me->id,
                'friend_id' => $friend->id,
                'status' => FriendshipStatus::PendingSent,
            ]);
        } elseif ($existing->status === FriendshipStatus::Blocked) {
            return $this->fail(__('user.friendships.error.blocked'), 403);
        } elseif ($existing->status === FriendshipStatus::Accepted) {
            return $this->fail(__('user.friendships.error.already_friends'), 409);
        } elseif ($existing->status === FriendshipStatus::PendingSent) {
            $message = $existing->user_id === $me->id
                ? __('user.friendships.request.already_sent')
                : __('user.friendships.request.they_sent');

            return $this->fail($message, 409);
        } else {
            // rejected：翻轉成邀請方這一側，重新送出邀請
            $existing->update([
                'user_id' => $me->id,
                'friend_id' => $friend->id,
                'status' => FriendshipStatus::PendingSent,
            ]);
        }

        $notification = Notifications::create([
            'user_id' => $friend->id,
            'sender_id' => $me->id,
            'type' => 'friend_request',
            'message' => __('user.friendships.notification.request', ['name' => $me->name]),
            'target_id' => $me->id,
        ]);
        NotificationCreated::dispatch($notification);

        return response()->json([
            'success' => true,
            'message' => __('user.friendships.request.sent'),
            'data' => ['friend_status' => 'pending_sent'],
        ]);
    }

    /**
     * Accept a received friend request.
     */
    public function acceptRequest(Request $request, User $friend): JsonResponse
    {
        $row = Friendships::query()
            ->where('user_id', $friend->id)
            ->where('friend_id', $request->user()->id)
            ->where('status', FriendshipStatus::PendingSent)
            ->firstOrFail();

        $row->update(['status' => FriendshipStatus::Accepted]);

        $notification = Notifications::create([
            'user_id' => $friend->id,
            'sender_id' => $request->user()->id,
            'type' => 'friend_accepted',
            'message' => __('user.friendships.notification.accepted', ['name' => $request->user()->name]),
            'target_id' => $request->user()->id,
        ]);
        NotificationCreated::dispatch($notification);

        return response()->json([
            'success' => true,
            'message' => __('user.friendships.request.accepted'),
            'data' => ['friend_status' => 'friend'],
        ]);
    }

    /**
     * Reject a received friend request (or withdraw a sent one).
     */
    public function rejectRequest(Request $request, User $friend): JsonResponse
    {
        Friendships::query()
            ->whereIn('user_id', [$request->user()->id, $friend->id])
            ->whereIn('friend_id', [$request->user()->id, $friend->id])
            ->where('status', FriendshipStatus::PendingSent)
            ->firstOrFail()
            ->update(['status' => FriendshipStatus::Rejected]);

        return response()->json([
            'success' => true,
            'message' => __('user.friendships.request.rejected'),
            'data' => ['friend_status' => 'none'],
        ]);
    }

    /**
     * Remove an existing friendship in either direction.
     */
    public function removeFriend(Request $request, User $friend): JsonResponse
    {
        $this->findBetween($request->user()->id, $friend->id)?->delete();

        return response()->json([
            'success' => true,
            'message' => __('user.friendships.friend.removed'),
            'data' => ['friend_status' => 'none'],
        ]);
    }

    /**
     * Find the friendship row between two users in either direction.
     */
    private function findBetween(int $a, int $b): ?Friendships
    {
        return Friendships::query()
            ->where(function ($query) use ($a, $b) {
                $query->where(fn ($q) => $q->where('user_id', $a)->where('friend_id', $b))
                    ->orWhere(fn ($q) => $q->where('user_id', $b)->where('friend_id', $a));
            })
            ->first();
    }

    private function fail(string $message, int $status): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}

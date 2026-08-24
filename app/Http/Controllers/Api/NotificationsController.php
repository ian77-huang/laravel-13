<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifications;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * List the authenticated user's latest notifications with unread count.
     */
    public function list(Request $request): JsonResponse
    {
        $user = $request->user();

        $items = $user->notifications()
            ->with('sender:id,name')
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn (Notifications $notification) => [
                'id' => $notification->id,
                'type' => $notification->type,
                'message' => $notification->message,
                'is_read' => $notification->is_read,
                'sender_name' => $notification->sender?->name,
                'created_at' => $notification->created_at?->toIso8601String(),
            ]);

        return response()->json([
            'unread_count' => $user->notifications()->unread()->count(),
            'items' => $items,
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function read(Request $request, Notifications $notification): JsonResponse
    {
        abort_unless($notification->user_id === $request->user()->id, 404);

        if ($notification->is_read === false) {
            $notification->markAsRead();
        }

        return response()->json([
            'success' => true,
            'unread_count' => $request->user()->notifications()->unread()->count(),
        ]);
    }

    /**
     * Mark all of the user's notifications as read.
     */
    public function readAll(Request $request): JsonResponse
    {
        $request->user()->notifications()->unread()->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }
}

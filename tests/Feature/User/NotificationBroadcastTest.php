<?php

use App\Events\NotificationCreated;
use App\Models\Notifications;
use App\Models\User;
use Illuminate\Support\Facades\Event;

it('broadcasts notification created event when a friend request is sent', function () {
    Event::fake([NotificationCreated::class]);

    $me = User::factory()->create();
    $friend = User::factory()->create();

    $this->actingAs($me)
        ->postJson("/api/user/friends/request/{$friend->id}")
        ->assertOk();

    Event::assertDispatched(NotificationCreated::class, function (NotificationCreated $event) use ($friend) {
        return $event->notification->user_id === $friend->id
            && $event->notification->type === 'friend_request'
            && $event->broadcastOn()[0]->name === "private-notifications.{$friend->id}"
            && $event->broadcastAs() === 'notification.created';
    });
});

it('broadcasts notification created event when a friend request is accepted', function () {
    Event::fake([NotificationCreated::class]);

    [$inviter, $acceptor] = User::factory()->count(2)->create();

    $this->actingAs($inviter)
        ->postJson("/api/user/friends/request/{$acceptor->id}")
        ->assertOk();

    $this->actingAs($acceptor)
        ->postJson("/api/user/friends/accept/{$inviter->id}")
        ->assertOk();

    Event::assertDispatched(NotificationCreated::class, function (NotificationCreated $event) use ($inviter) {
        return $event->notification->user_id === $inviter->id
            && $event->notification->type === 'friend_accepted';
    });

    Event::assertDispatchedTimes(NotificationCreated::class, 2);
});

it('does not broadcast when the invitation fails', function () {
    Event::fake([NotificationCreated::class]);

    $me = User::factory()->create();

    $this->actingAs($me)
        ->postJson("/api/user/friends/request/{$me->id}")
        ->assertStatus(422);

    Event::assertNothingDispatched();
});

it('broadcasts the unread count and sender name in the payload', function () {
    Event::fake([NotificationCreated::class]);

    $me = User::factory()->create();
    $alice = User::factory()->create(['name' => 'Alice']);
    $bob = User::factory()->create(['name' => 'Bob']);

    // Alice 已先送過一次邀請（未讀 1 筆）
    Notifications::factory()->create([
        'user_id' => $me->id,
        'sender_id' => $alice->id,
        'is_read' => false,
    ]);

    $this->actingAs($bob)
        ->postJson("/api/user/friends/request/{$me->id}")
        ->assertOk();

    Event::assertDispatched(NotificationCreated::class, function (NotificationCreated $event) {
        $payload = $event->broadcastWith();

        return $payload['unread_count'] === 2
            && $payload['sender_name'] === 'Bob'
            && $payload['is_read'] === false;
    });
});

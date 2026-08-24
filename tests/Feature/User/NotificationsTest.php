<?php

use App\Models\Notifications;
use App\Models\User;
use Carbon\CarbonInterface;

function createNotification(User $recipient, User $sender, bool $isRead = false, ?CarbonInterface $createdAt = null): Notifications
{
    return Notifications::factory()->create([
        'user_id' => $recipient->id,
        'sender_id' => $sender->id,
        'type' => 'friend_request',
        'message' => "{$sender->name} sent you a friend request.",
        'target_id' => $sender->id,
        'is_read' => $isRead,
        'created_at' => $createdAt ?? now(),
    ]);
}

it('requires authentication for notifications api', function () {
    $this->get('/api/user/notifications')->assertUnauthorized();
    $this->postJson('/api/user/notifications/read-all')->assertUnauthorized();
});

it('lists latest notifications with unread count and sender name', function () {
    $me = User::factory()->create();
    $alice = User::factory()->create(['name' => 'Alice']);
    $bob = User::factory()->create(['name' => 'Bob']);

    createNotification($me, $alice, createdAt: now()->subMinute());
    createNotification($me, $bob, isRead: true);

    $response = $this->actingAs($me)->getJson('/api/user/notifications');

    $response->assertOk()
        ->assertJsonPath('unread_count', 1)
        ->assertJsonCount(2, 'items');

    $items = collect($response->json('items'));

    expect($items->first()['sender_name'])->toBe('Bob')  // latest first
        ->and($items->last()['sender_name'])->toBe('Alice')
        ->and($items->last()['is_read'])->toBeFalse()
        ->and($items->pluck('message'))->toContain('Alice sent you a friend request.');
});

it('does not list other users notifications', function () {
    $me = User::factory()->create();
    $stranger = User::factory()->create();

    createNotification($stranger, $me);

    $this->actingAs($me)
        ->getJson('/api/user/notifications')
        ->assertOk()
        ->assertJsonPath('unread_count', 0)
        ->assertJsonCount(0, 'items');
});

it('marks a single notification as read', function () {
    $me = User::factory()->create();
    $other = User::factory()->create();
    $notification = createNotification($me, $other);

    $this->actingAs($me)
        ->postJson("/api/user/notifications/{$notification->id}/read")
        ->assertOk()
        ->assertJsonPath('unread_count', 0);

    expect($notification->refresh()->is_read)->toBeTrue();
});

it('cannot mark someone elses notification as read', function () {
    $me = User::factory()->create();
    $stranger = User::factory()->create();
    $notification = createNotification($stranger, $me);

    $this->actingAs($me)
        ->postJson("/api/user/notifications/{$notification->id}/read")
        ->assertNotFound();

    expect($notification->refresh()->is_read)->toBeFalse();
});

it('marks all notifications as read', function () {
    $me = User::factory()->create();
    $alice = User::factory()->create();
    $bob = User::factory()->create();

    createNotification($me, $alice);
    createNotification($me, $bob);
    createNotification($me, $alice, isRead: true);

    $this->actingAs($me)
        ->postJson('/api/user/notifications/read-all')
        ->assertOk()
        ->assertJsonPath('unread_count', 0);

    expect(Notifications::where('user_id', $me->id)->where('is_read', false)->count())->toBe(0)
        ->and(Notifications::count())->toBe(3);
});

<?php

use App\Enums\FriendshipStatus;
use App\Models\Friendships;
use App\Models\Notifications;
use App\Models\User;

function friendList(): array
{
    $response = test()->get('/api/user/friends/list');

    $response->assertOk();

    return collect($response->json('users'))->keyBy('id')->all();
}

it('requires authentication for the friends api', function () {
    $this->get('/api/user/friends/list')->assertUnauthorized();
    $this->postJson('/api/user/friends/request/1')->assertUnauthorized();
});

it('lists active non-admin users excluding myself with none status', function () {
    // migrations 內建 user1~user10 示範資料，因此用相對斷言而非總數
    $me = User::factory()->create();
    $alice = User::factory()->create(['name' => 'Alice']);
    $admin = User::factory()->create(['name' => 'Admin', 'is_admin' => true]);
    $disabled = User::factory()->create(['name' => 'Disabled', 'is_active' => false]);

    $this->actingAs($me);

    $users = friendList();
    $ids = array_keys($users);

    expect($ids)->not->toContain($me->id)
        ->and($ids)->toContain($alice->id)
        ->and($ids)->not->toContain($admin->id)
        ->and($ids)->not->toContain($disabled->id)
        ->and($users[$alice->id]['friend_status'])->toBe('none');
});

it('sends a friend request and notifies the receiver', function () {
    $me = User::factory()->create();
    $other = User::factory()->create();

    $this->actingAs($me)
        ->postJson("/api/user/friends/request/{$other->id}")
        ->assertOk()
        ->assertJsonPath('data.friend_status', 'pending_sent');

    expect(Friendships::where('user_id', $me->id)->where('friend_id', $other->id)->value('status'))
        ->toBe(FriendshipStatus::PendingSent)
        ->and(Notifications::where('user_id', $other->id)->where('type', 'friend_request')->count())->toBe(1);
});

it('rejects adding yourself as a friend', function () {
    $me = User::factory()->create();

    $this->actingAs($me)
        ->postJson("/api/user/friends/request/{$me->id}")
        ->assertStatus(422);
});

it('hides admin and inactive users from requests', function () {
    $me = User::factory()->create();
    $admin = User::factory()->create(['is_admin' => true]);
    $disabled = User::factory()->create(['is_active' => false]);

    $this->actingAs($me);

    $this->postJson("/api/user/friends/request/{$admin->id}")->assertNotFound();
    $this->postJson("/api/user/friends/request/{$disabled->id}")->assertNotFound();
});

it('prevents duplicate pending requests in either direction', function () {
    $me = User::factory()->create();
    $other = User::factory()->create();

    Friendships::factory()->create([
        'user_id' => $other->id,
        'friend_id' => $me->id,
        'status' => FriendshipStatus::PendingSent,
    ]);

    $this->actingAs($me)
        ->postJson("/api/user/friends/request/{$other->id}")
        ->assertStatus(409)
        ->assertJsonPath('success', false);
});

it('accepts a received request and both sides become friends', function () {
    $receiver = User::factory()->create();
    $sender = User::factory()->create();

    Friendships::factory()->create([
        'user_id' => $sender->id,
        'friend_id' => $receiver->id,
        'status' => FriendshipStatus::PendingSent,
    ]);

    $this->actingAs($receiver)
        ->postJson("/api/user/friends/accept/{$sender->id}")
        ->assertOk()
        ->assertJsonPath('data.friend_status', 'friend');

    expect(Friendships::first()->status)->toBe(FriendshipStatus::Accepted)
        ->and(Notifications::where('user_id', $sender->id)->where('type', 'friend_accepted')->count())->toBe(1)
        ->and(friendList()[$sender->id]['friend_status'])->toBe('friend');
});

it('cannot accept when no pending request exists', function () {
    $me = User::factory()->create();
    $stranger = User::factory()->create();

    $this->actingAs($me)
        ->postJson("/api/user/friends/accept/{$stranger->id}")
        ->assertNotFound();
});

it('rejects a received request and shows none afterwards', function () {
    $receiver = User::factory()->create();
    $sender = User::factory()->create();

    Friendships::factory()->create([
        'user_id' => $sender->id,
        'friend_id' => $receiver->id,
        'status' => FriendshipStatus::PendingSent,
    ]);

    $this->actingAs($receiver)
        ->postJson("/api/user/friends/reject/{$sender->id}")
        ->assertOk()
        ->assertJsonPath('data.friend_status', 'none');

    expect(Friendships::first()->status)->toBe(FriendshipStatus::Rejected)
        ->and(friendList()[$sender->id]['friend_status'])->toBe('none');
});

it('allows re-sending after rejection from the other side', function () {
    $a = User::factory()->create();
    $b = User::factory()->create();

    Friendships::factory()->create([
        'user_id' => $b->id,
        'friend_id' => $a->id,
        'status' => FriendshipStatus::Rejected,
    ]);

    $this->actingAs($a)
        ->postJson("/api/user/friends/request/{$b->id}")
        ->assertOk();

    expect(Friendships::first())
        ->user_id->toBe($a->id)
        ->friend_id->toBe($b->id)
        ->status->toBe(FriendshipStatus::PendingSent);
});

it('blocks sending to a blocked relationship', function () {
    $me = User::factory()->create();
    $other = User::factory()->create();

    Friendships::factory()->create([
        'user_id' => $me->id,
        'friend_id' => $other->id,
        'status' => FriendshipStatus::Blocked,
    ]);

    $this->actingAs($me)
        ->postJson("/api/user/friends/request/{$other->id}")
        ->assertStatus(403);
});

it('removes an existing friendship in any state', function () {
    $me = User::factory()->create();
    $other = User::factory()->create();

    Friendships::factory()->create([
        'user_id' => $other->id,
        'friend_id' => $me->id,
        'status' => FriendshipStatus::Accepted,
    ]);

    $this->actingAs($me)
        ->deleteJson("/api/user/friends/remove/{$other->id}")
        ->assertOk()
        ->assertJsonPath('data.friend_status', 'none');

    expect(Friendships::count())->toBe(0);
});

it('renders the friendships page with mapped statuses', function () {
    $me = User::factory()->create();
    $pending = User::factory()->create(['name' => 'Pending']);
    $friend = User::factory()->create(['name' => 'Buddy']);

    Friendships::factory()->create([
        'user_id' => $me->id,
        'friend_id' => $pending->id,
        'status' => FriendshipStatus::PendingSent,
    ]);
    Friendships::factory()->create([
        'user_id' => $friend->id,
        'friend_id' => $me->id,
        'status' => FriendshipStatus::Accepted,
    ]);

    $this->actingAs($me)
        ->get('/user/friends')
        ->assertOk()
        ->assertSee('Pending')
        ->assertSee('pending_sent')
        ->assertSee('friend');
});

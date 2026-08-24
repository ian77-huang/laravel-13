<?php

use App\Models\User;
use App\Services\BroadcastService;
use Illuminate\Broadcasting\AnonymousEvent;
use Illuminate\Support\Facades\Event;

function broadcastPayload(): array
{
    return [
        'title' => 'Hello',
        'message' => 'Maintenance tonight',
        'type' => 'warning',
    ];
}

function channelName(mixed $channel): string
{
    return is_string($channel) ? $channel : $channel->name;
}

function dispatchedChannels(): array
{
    return collect(Event::dispatched(AnonymousEvent::class))
        ->flatten()
        ->map(fn (AnonymousEvent $event) => channelName($event->broadcastOn()[0]))
        ->all();
}

it('broadcasts to individual users on private notifications channels', function () {
    Event::fake();

    [$alice, $bob] = User::factory()->count(2)->create();

    app(BroadcastService::class)->toUsers([$alice->id, $bob->id, $alice->id], broadcastPayload());

    $channels = dispatchedChannels();

    expect($channels)->toBe([
        "private-notifications.{$alice->id}",
        "private-notifications.{$bob->id}",
    ]);
});

it('broadcasts the message payload and event name', function () {
    Event::fake();

    [$alice] = User::factory()->count(1)->create();

    app(BroadcastService::class)->toUsers([$alice->id], broadcastPayload());

    Event::assertDispatched(AnonymousEvent::class, function (AnonymousEvent $event) use ($alice) {
        return $event->broadcastAs() === 'broadcast.message'
            && $event->broadcastWith() === broadcastPayload()
            && $event->broadcastOn()[0]->name === "private-notifications.{$alice->id}";
    });
});

it('keeps broadcasting to everyone on the public broadcast.all channel', function () {
    Event::fake();

    app(BroadcastService::class)->toAll(broadcastPayload());

    Event::assertDispatched(AnonymousEvent::class, function (AnonymousEvent $event) {
        return channelName($event->broadcastOn()[0]) === 'broadcast.all'
            && $event->broadcastAs() === 'broadcast.message';
    });
});

<?php

namespace App\Services;

use Illuminate\Support\Facades\Broadcast;

class BroadcastService
{
    /**
     * Broadcast a message to everyone on the public channel.
     *
     * @param  array<string, mixed>  $payload
     */
    public function toAll(array $payload): void
    {
        Broadcast::on('broadcast.all')
            ->as('broadcast.message')
            ->with($payload)
            ->send();
    }

    /**
     * Broadcast a message privately to the given users.
     *
     * @param  array<int, int>  $userIds
     * @param  array<string, mixed>  $payload
     */
    public function toUsers(array $userIds, array $payload): void
    {
        foreach (array_unique($userIds) as $userId) {
            Broadcast::private("notifications.{$userId}")
                ->as('broadcast.message')
                ->with($payload)
                ->send();
        }
    }
}

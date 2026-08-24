<?php

namespace Database\Factories;

use App\Enums\FriendshipStatus;
use App\Models\Friendships;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Friendships>
 */
class FriendshipsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'friend_id' => User::factory(),
            'status' => FriendshipStatus::PendingSent,
        ];
    }
}

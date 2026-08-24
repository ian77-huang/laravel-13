<?php

namespace Database\Factories;

use App\Models\Notifications;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notifications>
 */
class NotificationsFactory extends Factory
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
            'sender_id' => User::factory(),
            'type' => 'friend_request',
            'message' => fake()->sentence(),
            'target_id' => 0,
            'is_read' => false,
        ];
    }
}

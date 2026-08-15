<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's default admin user.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin',
                'password' => '12345678',
                'email_verified_at' => now(),
                'is_active' => true,
                'is_admin' => true,
            ],
        );

        $user->profile()->updateOrCreate([], [
            'name' => 'admin',
            'email' => 'admin@example.com',
        ]);
    }
}

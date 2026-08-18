<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $password = Hash::make('12345678');

        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "user{$i}",
                'email' => "user{$i}@example.com",
                'password' => $password,
                'email_verified_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        User::whereIn('email', array_map(fn ($i) => "user{$i}@example.com", range(1, 10)))->delete();
    }
};

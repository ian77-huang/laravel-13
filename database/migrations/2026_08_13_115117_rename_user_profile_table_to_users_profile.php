<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('user_profile') && ! Schema::hasTable('users_profile')) {
            Schema::rename('user_profile', 'users_profile');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users_profile') && ! Schema::hasTable('user_profile')) {
            Schema::rename('users_profile', 'user_profile');
        }
    }
};

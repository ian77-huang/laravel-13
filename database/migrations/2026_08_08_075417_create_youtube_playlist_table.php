<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('youtube_playlists', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('channelId');
            $table->string('title');
            $table->unsignedInteger('cId');
            $table->json('image');
            $table->json('player');
            $table->timestamp('publishedAt');
            $table->text('description');
            $table->string('channelTitle')->default('');
            $table->string('etag')->default('');
            $table->index('channelId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youtube_playlists');
    }
};

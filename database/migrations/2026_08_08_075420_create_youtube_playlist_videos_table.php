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
        Schema::create('youtube_playlist_videos', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('playlistId');
            $table->string('videoId');
            $table->unique(['playlistId', 'videoId']);
            $table->index('playlistId');
            $table->index('videoId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youtube_playlist_videos');
    }
};

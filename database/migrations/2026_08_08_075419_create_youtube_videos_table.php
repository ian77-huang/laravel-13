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
        Schema::create('youtube_videos', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('channelId');
            $table->string('title');
            $table->text('description');
            $table->integer('position');
            $table->timestamp('publishedAt');
            $table->json('image');
            $table->json('resourceId');
            $table->string('videoOwnerChannelId');
            $table->string('videoOwnerChannelTitle');
            $table->json('tags')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youtube_videos');
    }
};

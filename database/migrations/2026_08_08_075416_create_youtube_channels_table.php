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
        Schema::create('youtube_channels', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->json('branding');
            $table->unsignedInteger('cId');
            $table->string('title');
            $table->text('description');
            $table->json('image');
            $table->timestamp('publishedAt');
            $table->text('keywords')->nullable();
            $table->string('customUrl')->nullable();
            $table->integer('state')->default(0);
            $table->string('etag')->default('');
            $table->index('state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youtube_channels');
    }
};

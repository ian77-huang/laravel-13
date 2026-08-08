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
        Schema::create('bible_books', function (Blueprint $table) {
            $table->id();
            $table->string('abbreviation');
            $table->string('language');
            $table->string('name');
            $table->enum('testament', ['OT', 'NT']);
            $table->unsignedInteger('chapter_count');
            $table->unique(['abbreviation', 'language']);
            $table->index('language');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bible_books');
    }
};

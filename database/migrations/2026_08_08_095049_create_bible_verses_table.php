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
        Schema::create('bible_verses', function (Blueprint $table) {
            $table->id();
            $table->string('language');
            $table->foreignId('book_id')->constrained('bible_books')->cascadeOnDelete();
            $table->unsignedInteger('chapter');
            $table->unsignedInteger('verse');
            $table->text('text');
            $table->foreignId('verse_ref_id')->constrained('bible_verse_refs')->cascadeOnDelete();
            $table->unique(['language', 'book_id', 'chapter', 'verse']);
            $table->index('language');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bible_verses');
    }
};

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
        Schema::create('bible_verse_refs', function (Blueprint $table) {
            $table->id();
            $table->string('abbreviation');
            $table->unsignedInteger('chapter');
            $table->unsignedInteger('verse');
            $table->unique(['abbreviation', 'chapter', 'verse']);
            $table->index('abbreviation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bible_verse_refs');
    }
};

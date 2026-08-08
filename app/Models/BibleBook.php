<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['abbreviation', 'language', 'name', 'testament', 'chapter_count'])]
class BibleBook extends Model
{
    public $timestamps = false;

    /**
     * Get the verses for this book.
     *
     * @return HasMany<BibleVerse, $this>
     */
    public function verses(): HasMany
    {
        return $this->hasMany(BibleVerse::class, 'book_id');
    }
}

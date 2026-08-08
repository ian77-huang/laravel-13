<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['language', 'book_id', 'chapter', 'verse', 'text', 'verse_ref_id'])]
class BibleVerse extends Model
{
    public $timestamps = false;

    /**
     * Get the book this verse belongs to.
     *
     * @return BelongsTo<BibleBook, $this>
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(BibleBook::class, 'book_id');
    }

    /**
     * Get the canonical reference this verse belongs to.
     *
     * @return BelongsTo<BibleVerseRef, $this>
     */
    public function verseRef(): BelongsTo
    {
        return $this->belongsTo(BibleVerseRef::class, 'verse_ref_id');
    }
}

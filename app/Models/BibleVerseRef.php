<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['abbreviation', 'chapter', 'verse'])]
class BibleVerseRef extends Model
{
    public $timestamps = false;

    /**
     * Get the translated verses for this reference.
     *
     * @return HasMany<BibleVerse, $this>
     */
    public function verses(): HasMany
    {
        return $this->hasMany(BibleVerse::class, 'verse_ref_id');
    }
}

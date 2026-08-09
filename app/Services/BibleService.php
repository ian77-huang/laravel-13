<?php

namespace App\Services;

use App\Models\BibleVerseRef;
use App\Support\Cache\CacheHub;
use App\Support\MathCraft;
use App\Support\TimeCraft;
use stdClass;

class BibleService
{
    public function __construct(private readonly CacheHub $cache) {}

    /**
     * Get a verse in the given language by its canonical reference id.
     *
     * Equivalent to:
     *   SELECT bible_books.name, bible_verse_refs.chapter, bible_verse_refs.verse,
     *          bible_verses.text
     *   FROM bible_verse_refs
     *   LEFT JOIN bible_verses ON bible_verse_refs.id = bible_verses.verse_ref_id
     *   LEFT JOIN bible_books
     *     ON bible_verses.book_id = bible_books.id
     *     AND bible_books.language = bible_verses.language
     *   WHERE bible_verse_refs.id = $refId AND bible_verses.language = $language
     */
    public function findVerseByToday(string $language = 'zh-TW'): ?stdClass
    {
        $cacheKey = constants('Cache_Key_Index_Bible').$language;
        $seconds = TimeCraft::toMidnightSeconds();

        return $this->cache->remember($cacheKey, function () use ($language) {
            $rand = MathCraft::seededRandomNumber(date('Y-m-d'));

            return BibleVerseRef::query()
                ->leftJoin('bible_verses', 'bible_verse_refs.id', '=', 'bible_verses.verse_ref_id')
                ->leftJoin('bible_books', 'bible_verses.book_id', '=', 'bible_books.id')
                ->whereColumn('bible_books.language', '=', 'bible_verses.language', 'and')
                ->where('bible_verse_refs.id', $rand)
                ->where('bible_verses.language', $language)
                ->select([
                    'bible_books.name as book_name',
                    'bible_verse_refs.chapter',
                    'bible_verse_refs.verse',
                    'bible_verses.text',
                ])
                ->toBase()
                ->first();
        }, $seconds);
    }
}

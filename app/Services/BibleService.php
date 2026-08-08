<?php

namespace App\Services;

use App\Models\BibleVerseRef;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use stdClass;

class BibleService
{
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

        $CACHE_KEY_BIBLE = 'Bible:Today:'.$language;

        $bible = Cache::get($CACHE_KEY_BIBLE);
        if (! $bible) {
            $bible = unserialize(Redis::get($CACHE_KEY_BIBLE));
            if (! $bible) {
                $dateStr = date('Y-m-d');
                $rand = hexdec(substr(hash('sha256', $dateStr), 0, 8)) % 124 + 1;

                $bible = BibleVerseRef::query()
                    ->leftJoin('bible_verses', 'bible_verse_refs.id', '=', 'bible_verses.verse_ref_id')
                    ->leftJoin('bible_books', 'bible_verses.book_id', '=', 'bible_books.id')
                    ->whereColumn('bible_books.language', '=', 'bible_verses.language', 'and')
                    ->where('bible_verse_refs.id', $rand)
                    ->where('bible_verses.language', $language)
                    ->select(
                        ['bible_books.name as book_name',
                            'bible_verse_refs.chapter',
                            'bible_verse_refs.verse',
                            'bible_verses.text']
                    )
                    ->toBase()
                    ->first();
                if ($bible) {
                    $now = time();
                    $endOfDay = mktime(23, 59, 59, (int) date('m'), (int) date('d'), (int) date('Y'));
                    $seconds = $endOfDay - $now;

                    Redis::setex($CACHE_KEY_BIBLE, $seconds, serialize($bible));
                    Cache::put($CACHE_KEY_BIBLE, $bible, $seconds);
                }
            }
        }

        return $bible;
    }
}

<?php

namespace App\Services;

use App\Models\YoutubeChannel;
use App\Support\Cache\CacheHub;
use stdClass;

class YoutubeService
{
    public function __construct(private readonly CacheHub $cache) {}


    public function getChannels(): ?stdClass
    {
        // $cacheKey = 'Bible:Today:'.$language;
        // $seconds = TimeCraft::toMidnightSeconds();

        // return $this->cache->remember($cacheKey, function () use ($language) {
        //     $rand = MathCraft::seededRandomNumber(date('Y-m-d'));

        //     return BibleVerseRef::query()
        //         ->leftJoin('bible_verses', 'bible_verse_refs.id', '=', 'bible_verses.verse_ref_id')
        //         ->leftJoin('bible_books', 'bible_verses.book_id', '=', 'bible_books.id')
        //         ->whereColumn('bible_books.language', '=', 'bible_verses.language', 'and')
        //         ->where('bible_verse_refs.id', $rand)
        //         ->where('bible_verses.language', $language)
        //         ->select([
        //             'bible_books.name as book_name',
        //             'bible_verse_refs.chapter',
        //             'bible_verse_refs.verse',
        //             'bible_verses.text',
        //         ])
        //         ->toBase()
        //         ->first();
        // }, $seconds);
        return null;
    }
}

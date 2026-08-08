<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BibleVerseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = json_decode(File::get(database_path('seeders/data/bible_verses.json')), true);

        $books = DB::table('bible_books')
            ->get()
            ->keyBy(fn (object $book): string => $book->abbreviation.'|'.$book->language);

        $verseRefs = DB::table('bible_verse_refs')
            ->get()
            ->keyBy(fn (object $ref): string => $ref->abbreviation.'|'.$ref->chapter.'|'.$ref->verse);

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('bible_verses')->insert(array_map(function (array $row) use ($books, $verseRefs): array {
                return [
                    'language' => $row['language'],
                    'book_id' => $books->get($row['abbreviation'].'|'.$row['language'])->id,
                    'chapter' => $row['chapter'],
                    'verse' => $row['verse'],
                    'text' => $row['text'],
                    'verse_ref_id' => $verseRefs->get($row['abbreviation'].'|'.$row['chapter'].'|'.$row['verse'])->id,
                ];
            }, $chunk));
        }
    }
}

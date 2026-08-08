<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BibleVerseRefSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = json_decode(File::get(database_path('seeders/data/bible_verse_refs.json')), true);

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('bible_verse_refs')->insert(array_map(function (array $row): array {
                return [
                    'abbreviation' => $row['abbreviation'],
                    'chapter' => $row['chapter'],
                    'verse' => $row['verse'],
                ];
            }, $chunk));
        }
    }
}

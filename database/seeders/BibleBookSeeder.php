<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BibleBookSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = json_decode(File::get(database_path('seeders/data/bible_books.json')), true);

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('bible_books')->insert(array_map(function (array $row): array {
                return [
                    'abbreviation' => $row['abbreviation'],
                    'language' => $row['language'],
                    'name' => $row['name'],
                    'testament' => $row['testament'],
                    'chapter_count' => $row['chapter_count'],
                ];
            }, $chunk));
        }
    }
}

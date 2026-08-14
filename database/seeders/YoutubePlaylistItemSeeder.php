<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YoutubePlaylistItemSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = fopen(database_path('seeders/data/youtube_playlist_items.json'), 'r');

        $rows = [];

        while (($line = fgets($file)) !== false) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $rows[] = json_decode($line, true);

            if (count($rows) === 500) {
                $this->insertRows($rows);

                $rows = [];
            }
        }

        if ($rows !== []) {
            $this->insertRows($rows);
        }

        fclose($file);
    }

    private function insertRows(array $rows): void
    {
        DB::table('youtube_playlist_items')->insert(array_map(function (array $row): array {
            return [
                'id' => $row['id'],
                'playlistId' => $row['playlistId'],
                'data' => json_encode($row['data']),
                'createdAt' => $row['createdAt'],
                'updatedAt' => $row['updatedAt'],
            ];
        }, $rows));
    }
}

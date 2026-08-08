<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class YoutubePlaylistItemSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = json_decode(File::get(database_path('seeders/data/youtube_playlist_items.json')), true);

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('youtube_playlist_items')->insert(array_map(function (array $row): array {
                return [
                    'id' => $row['id'],
                    'playlistId' => $row['playlistId'],
                    'data' => json_encode($row['data']),
                    'createdAt' => $row['createdAt'],
                    'updatedAt' => $row['updatedAt'],
                ];
            }, $chunk));
        }
    }
}

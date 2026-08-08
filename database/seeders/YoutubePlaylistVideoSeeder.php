<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class YoutubePlaylistVideoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = json_decode(File::get(database_path('seeders/data/youtube_playlist_videos.json')), true);

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('youtube_playlist_videos')->insert(array_map(function (array $row): array {
                return [
                    'id' => $row['id'],
                    'playlistId' => $row['playlistId'],
                    'videoId' => $row['videoId'],
                ];
            }, $chunk));
        }
    }
}

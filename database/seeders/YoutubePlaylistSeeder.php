<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class YoutubePlaylistSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = json_decode(File::get(database_path('seeders/data/youtube_playlist.json')), true);

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('youtube_playlists')->insert(array_map(function (array $row): array {
                return [
                    'id' => $row['id'],
                    'channelId' => $row['channelId'],
                    'title' => $row['title'],
                    'cId' => $row['cId'],
                    'image' => json_encode($row['image']),
                    'player' => json_encode($row['player']),
                    'publishedAt' => $row['publishedAt'],
                    'description' => $row['description'],
                    'channelTitle' => $row['channelTitle'],
                    'etag' => $row['etag'],
                ];
            }, $chunk));
        }
    }
}

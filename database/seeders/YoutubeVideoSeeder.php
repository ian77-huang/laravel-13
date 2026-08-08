<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class YoutubeVideoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = json_decode(File::get(database_path('seeders/data/youtube_videos.json')), true);

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('youtube_videos')->insert(array_map(function (array $row): array {
                return [
                    'id' => $row['id'],
                    'channelId' => $row['channelId'],
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'position' => $row['position'],
                    'publishedAt' => $row['publishedAt'],
                    'image' => json_encode($row['image']),
                    'resourceId' => json_encode($row['resourceId']),
                    'videoOwnerChannelId' => $row['videoOwnerChannelId'],
                    'videoOwnerChannelTitle' => $row['videoOwnerChannelTitle'],
                    'tags' => isset($row['tags']) ? json_encode($row['tags']) : null,
                ];
            }, $chunk));
        }
    }
}

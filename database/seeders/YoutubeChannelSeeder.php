<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class YoutubeChannelSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = json_decode(File::get(database_path('seeders/data/youtube_channels.json')), true);

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('youtube_channels')->insert(array_map(function (array $row): array {
                return [
                    'id' => $row['id'],
                    'branding' => json_encode($row['branding']),
                    'cId' => $row['cId'],
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'image' => json_encode($row['image']),
                    'publishedAt' => $row['publishedAt'],
                    'keywords' => $row['keywords'] ?? null,
                    'customUrl' => $row['customUrl'] ?? null,
                    'state' => $row['state'],
                    'etag' => $row['etag'],
                ];
            }, $chunk));
        }
    }
}

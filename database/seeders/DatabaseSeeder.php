<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            YoutubeChannelSeeder::class,
            YoutubePlaylistSeeder::class,
            YoutubePlaylistItemSeeder::class,
            YoutubeVideoSeeder::class,
            YoutubePlaylistVideoSeeder::class,
            BibleBookSeeder::class,
            BibleVerseRefSeeder::class,
            BibleVerseSeeder::class,
        ]);
    }
}

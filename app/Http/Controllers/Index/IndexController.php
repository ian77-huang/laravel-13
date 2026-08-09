<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Services\BibleService;
use App\Services\YoutubeService;
use Laravel\Octane\Facades\Octane;

class IndexController extends Controller
{
    public function index()
    {
        [$verse, $youtubeChannels] = Octane::concurrently([
            fn () => app(BibleService::class)->findVerseByToday('zh-TW'),
            fn () => app(YoutubeService::class)->getChannels(),
        ]);

        return view('frontend.index', [
            'bible' => $verse,
            "youtubeChannels" => $youtubeChannels
        ]);
    }
}

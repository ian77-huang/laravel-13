<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;

// use App\Services\BibleService;
// use App\Services\YoutubeService;
// use Laravel\Octane\Facades\Octane;

class IndexController extends Controller
{
    public function index()
    {
        // $locale = app()->getLocale();
        // [$verse, $youtubeChannels] = Octane::concurrently([
        //     fn () => app(BibleService::class)->findVerseByToday($locale),
        //     fn () => app(YoutubeService::class)->getChannels(),
        // ]);

        return view('frontend.user.index', [
            // 'bible' => $verse,
            // 'youtubeChannels' => $youtubeChannels,
        ]);
    }
}

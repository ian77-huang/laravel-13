<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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

        $user = Auth::user();
        $profile = $user->profile;

        return view('frontend.user.index', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }
}

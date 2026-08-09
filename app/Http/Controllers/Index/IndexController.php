<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Services\BibleService;
use Laravel\Octane\Facades\Octane;

class IndexController extends Controller
{
    public function index()
    {
        [$verse] = Octane::concurrently([
            fn () => app(BibleService::class)->findVerseByToday('zh-TW'),
        ]);

        return view('frontend.index', [
            'bible' => $verse,
        ]);
    }
}

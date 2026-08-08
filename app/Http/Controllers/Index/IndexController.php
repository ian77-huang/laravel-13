<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Services\BibleService;
use Laravel\Octane\Facades\Octane;

class IndexController extends Controller
{
    public function index(BibleService $bible)
    {
        [$verse] = Octane::concurrently([
            fn () => $bible->findVerseByToday('zh-TW'),
        ]);

        return view('frontend.index', [
            'bible' => $verse,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeLocaleRequest;

class LocaleController extends Controller
{
    public function change(ChangeLocaleRequest $request)
    {
        $locale = $request->validated('locale');

        app()->setLocale($locale);
        $request->session()->put('locale', $locale);

        return response()
            ->json(['locale' => $locale])
            ->cookie('locale', $locale, 60 * 24 * 30);
    }
}

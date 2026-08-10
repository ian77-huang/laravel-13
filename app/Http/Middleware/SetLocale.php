<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locales = config('app.available_locales'); // 白名單
        $locale = $request->cookie('locale');
        // $locale = $request->segment(1)
        //         ?? $request->session()->get('locale')
        //         ?? $request->cookie('locale')
        //         ?? $request->getPreferredLanguage($locales);

        if (! in_array($locale, $locales)) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);

        return $next($request);

    }
}

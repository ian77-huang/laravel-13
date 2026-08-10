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
        $locale = $request->segment(1)           // ① 從 URL 前綴
                ?? $request->session()->get('locale') // ② 從 session
                ?? $request->cookie('locale')        // ③ 從 cookie
                ?? $request->getPreferredLanguage($locales); // ④ 從瀏覽器 Accept-Language

        if (! in_array($locale, $locales)) {
            $locale = config('app.locale');         // 不合規格 → 回預設
        }

        app()->setLocale($locale);

        return $next($request);

    }
}

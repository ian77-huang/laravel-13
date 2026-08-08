<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventHttpCache
{
    /**
     * 禁止瀏覽器快取，確保每次 request 都重新抓取（測試負載平衡時避免快取遮蔽）。
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! app()->environment('local')) {
            return $response;
        }

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Wed, 01 Jan 1997 00:00:00 GMT');

        return $response;
    }
}

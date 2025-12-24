<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceUtf8
{
    /**
     * Ensure HTML responses are served as UTF-8 so accents render correctly.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $contentType = $response->headers->get('Content-Type', '');

        if ($contentType === '' || stripos($contentType, 'text/html') === 0) {
            $response->headers->set('Content-Type', 'text/html; charset=UTF-8');
        }

        return $response;
    }
}

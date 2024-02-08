<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class CheckInactivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $timeout = 2;

        if (time() - session('last_heartbeat') > $timeout) {
            Session::flush();
            return redirect(route('login'));
        }

        session(['last_heartbeat' => time()]);

        return $next($request);
    }
}

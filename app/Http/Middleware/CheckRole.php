<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{

    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user()->fresh();

        if (!$user || !$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Tu cuenta no está activa.');
        }

        $allowedRoles = is_array($roles) ? $roles : [$roles];

        if (!in_array($user->role_id, $allowedRoles)) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}

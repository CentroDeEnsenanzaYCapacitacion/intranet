<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|array  $roles  Roles permitidos (ej: '1' o '1,2,6')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Recargar el usuario desde la base de datos para obtener el rol actualizado
        $user = auth()->user()->fresh();

        if (!$user || !$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Tu cuenta no está activa.');
        }

        // Convertir roles a array
        $allowedRoles = is_array($roles) ? $roles : [$roles];

        // Verificar si el rol del usuario está en los roles permitidos
        if (!in_array($user->role_id, $allowedRoles)) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}

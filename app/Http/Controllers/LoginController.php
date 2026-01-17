<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }
    public function attemptLogin(LoginRequest $request)
    {
        $throttleKey = Str::lower($request->username) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return redirect(route('login'))->with('error', 'Demasiados intentos. Intenta de nuevo en ' . ceil($seconds / 60) . ' minutos.');
        }

        $credentials = [
            'username' => trim($request->username),
            'password' => trim($request->password),
            'is_active' => true,
        ];

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($throttleKey);
            Auth::user()->update(['last_login' => now()]);

            return redirect()->intended(route('dashboard'));
        }

        RateLimiter::hit($throttleKey, 300);

        Log::warning('Login fallido', [
            'username' => $request->username,
            'ip' => $request->ip()
        ]);

        return redirect(route('login'))->with('error', 'Credenciales incorrectas.');
    }

    public function logout()
    {
        session::flush();
        return redirect()->route('login');
    }
}

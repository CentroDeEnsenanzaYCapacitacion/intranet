<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }
    public function attemptLogin(LoginRequest $request)
    {

        $credentials = [
            'username' => trim($request->username),
            'password' => trim($request->password),
            'is_active' => true,
        ];

        if (Auth::attempt($credentials)) {

            Auth::user()->update(['last_login' => now()]);

            return redirect()->intended(route('dashboard'));
        }

        return redirect(route('login'))->with('error', 'Credenciales incorrectas.');
    }

    public function logout()
    {
        session::flush();
        return redirect()->route('login');
    }
}

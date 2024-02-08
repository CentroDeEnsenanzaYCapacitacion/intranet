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

        $credentials = $request->only('username', 'password');

        if(Auth::attempt($credentials)) {
            //session::put('last_heartbeat',time());
            return redirect()->intended(route('dashboard'));
        }

        return redirect(route('login'))->with('error', 'credenciales incorrectas');

    }
}

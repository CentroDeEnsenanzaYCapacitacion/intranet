<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfirmPasswordController extends Controller
{
    public function show()
    {
        return view('auth.confirm-password');
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'La contraseña es requerida.',
            'password.current_password' => 'La contraseña es incorrecta.',
        ]);

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard'));
    }

    public function confirmAjax(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'La contraseña es requerida.',
            'password.current_password' => 'La contraseña es incorrecta.',
        ]);

        $request->session()->put('auth.password_confirmed_at', time());

        return response()->json(['confirmed' => true]);
    }

    public function status(Request $request)
    {
        $confirmedAt = $request->session()->get('auth.password_confirmed_at', 0);
        $timeout = config('auth.password_timeout', 1800);
        $confirmed = (time() - $confirmedAt) < $timeout;

        return response()->json(['confirmed' => $confirmed]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserInvitation;
use Illuminate\Support\Facades\Hash;

class SetPasswordController extends Controller
{
    public function showSetPasswordForm(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Token inválido.');
        }

        $invitation = UserInvitation::where('token', $token)->first();

        if (!$invitation || !$invitation->isValid()) {
            return redirect()->route('login')->with('error', 'Este enlace ha expirado o ya fue utilizado.');
        }

        return view('auth.set-password', compact('token'));
    }

    public function setPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ],
        ], [
            'password.required' => 'La contraseña es requerida.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe incluir mayúsculas, minúsculas, números y símbolos (@$!%*?&).',
        ]);

        $invitation = UserInvitation::where('token', $request->token)->first();

        if (!$invitation || !$invitation->isValid()) {
            return back()->with('error', 'Este enlace ha expirado o ya fue utilizado.');
        }

        $user = $invitation->user;
        $user->password = Hash::make($request->password);
        $user->save();

        $invitation->used = true;
        $invitation->save();

        return redirect()->route('login')->with('success', 'Contraseña establecida correctamente. Ya puedes iniciar sesión.');
    }
}

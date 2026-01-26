<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserInvitation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
                'confirmed',
                Password::min(12)->mixedCase()->numbers()->symbols()->uncompromised(),
            ],
        ], [
            'password.required' => 'La contraseña es requerida.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 12 caracteres.',
            'password.mixed' => 'La contraseña debe incluir mayúsculas y minúsculas.',
            'password.numbers' => 'La contraseña debe incluir al menos un número.',
            'password.symbols' => 'La contraseña debe incluir al menos un símbolo.',
            'password.uncompromised' => 'La contraseña ha aparecido en filtraciones. Usa otra.',
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

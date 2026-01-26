<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{

    public function showChangeForm()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                'different:current_password',
                Password::min(12)->mixedCase()->numbers()->symbols()->uncompromised(),
            ],
        ], [
            'current_password.required' => 'La contraseña actual es requerida.',
            'password.required' => 'La nueva contraseña es requerida.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.different' => 'La nueva contraseña debe ser diferente a la actual.',
            'password.min' => 'La nueva contraseña debe tener al menos 12 caracteres.',
            'password.mixed' => 'La nueva contraseña debe incluir mayúsculas y minúsculas.',
            'password.numbers' => 'La nueva contraseña debe incluir al menos un número.',
            'password.symbols' => 'La nueva contraseña debe incluir al menos un símbolo.',
            'password.uncompromised' => 'La nueva contraseña ha aparecido en filtraciones. Usa otra.',
        ]);

        $user = Auth::user();

        $currentPassword = trim($request->current_password);
        $newPassword = trim($request->password);

        if (!Hash::check($currentPassword, $user->password)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual es incorrecta.',
            ]);
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        return back()->with('success', 'Tu contraseña ha sido actualizada exitosamente.');
    }
}

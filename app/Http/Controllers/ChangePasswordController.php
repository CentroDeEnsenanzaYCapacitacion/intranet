<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
                'min:8',
                'confirmed',
                'different:current_password',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ],
        ], [
            'current_password.required' => 'La contraseña actual es requerida.',
            'password.required' => 'La nueva contraseña es requerida.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.different' => 'La nueva contraseña debe ser diferente a la actual.',
            'password.regex' => 'La contraseña debe incluir mayúsculas, minúsculas, números y símbolos (@$!%*?&).',
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

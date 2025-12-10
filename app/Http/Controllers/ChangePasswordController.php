<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /**
     * Muestra el formulario para cambiar contraseña
     */
    public function showChangeForm()
    {
        return view('auth.change-password');
    }

    /**
     * Procesa el cambio de contraseña
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed|different:current_password',
        ], [
            'current_password.required' => 'La contraseña actual es requerida.',
            'password.required' => 'La nueva contraseña es requerida.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.different' => 'La nueva contraseña debe ser diferente a la actual.',
        ]);

        $user = Auth::user();

        // Limpiar espacios en blanco de las contraseñas
        $currentPassword = trim($request->current_password);
        $newPassword = trim($request->password);

        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($currentPassword, $user->password)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual es incorrecta.',
            ]);
        }

        // Actualizar contraseña
        $user->password = Hash::make($newPassword);
        $user->save();

        return back()->with('success', 'Tu contraseña ha sido actualizada exitosamente.');
    }
}

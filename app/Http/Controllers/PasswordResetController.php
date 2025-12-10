<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ResetPassword;

class PasswordResetController extends Controller
{
    /**
     * Muestra el formulario para solicitar reseteo de contraseña
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envía el correo con el enlace de reseteo
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'El correo electrónico es requerido.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.exists' => 'No existe una cuenta con este correo electrónico.',
        ]);

        // Verificar que el usuario esté activo
        $user = User::where('email', $request->email)->first();

        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Esta cuenta está desactivada. Contacte al administrador.',
            ]);
        }

        // Eliminar tokens anteriores del usuario
        PasswordReset::where('email', $request->email)->delete();

        // Generar token único
        $token = Str::random(64);

        // Guardar token en la base de datos
        PasswordReset::create([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Enviar correo
        try {
            Mail::to($request->email)->send(new ResetPassword($user, $token));

            return back()->with('success', 'Se ha enviado un correo con las instrucciones para restablecer tu contraseña.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Error al enviar el correo. Por favor intenta de nuevo.',
            ]);
        }
    }

    /**
     * Muestra el formulario para restablecer la contraseña con el token
     */
    public function showResetForm($token)
    {
        // Verificar que el token existe
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset) {
            return redirect()->route('login')->withErrors([
                'token' => 'El enlace de recuperación no es válido o ha expirado.',
            ]);
        }

        // Verificar que el token no haya expirado (1 hora)
        if (now()->diffInMinutes($passwordReset->created_at) > 60) {
            PasswordReset::where('token', $token)->delete();
            return redirect()->route('login')->withErrors([
                'token' => 'El enlace de recuperación ha expirado. Por favor solicita uno nuevo.',
            ]);
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $passwordReset->email]);
    }

    /**
     * Procesa el reseteo de la contraseña
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required' => 'La contraseña es requerida.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        // Verificar token
        $passwordReset = PasswordReset::where([
            'token' => $request->token,
            'email' => $request->email,
        ])->first();

        if (!$passwordReset) {
            return back()->withErrors([
                'token' => 'El token de recuperación no es válido.',
            ]);
        }

        // Verificar que no haya expirado
        if (now()->diffInMinutes($passwordReset->created_at) > 60) {
            PasswordReset::where('token', $request->token)->delete();
            return back()->withErrors([
                'token' => 'El enlace de recuperación ha expirado.',
            ]);
        }

        // Actualizar contraseña
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make(trim($request->password));
        $user->save();

        // Eliminar el token usado
        PasswordReset::where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Tu contraseña ha sido restablecida exitosamente.');
    }
}

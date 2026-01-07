<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Crew;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {

        $this->middleware('role:1,2,6');
    }

    public function getUsers()
    {
        $currentUser = Auth::user();

        if ($currentUser->role_id == 1) {
            $users = User::with('invitation')->where('is_active', true)->where('id', '!=', 1)->get();
            $blocked_users = User::with('invitation')->where('is_active', false)->where('id', '!=', 1)->get();
        }

        elseif ($currentUser->role_id == 6) {
            $users = User::with('invitation')->where('is_active', true)
                ->whereIn('role_id', [4, 6])
                ->where('id', '!=', 1)
                ->get();
            $blocked_users = User::with('invitation')->where('is_active', false)
                ->whereIn('role_id', [4, 6])
                ->where('id', '!=', 1)
                ->get();
        }

        else {
            $users = User::with('invitation')->where('is_active', true)
                ->where('crew_id', $currentUser->crew_id)
                ->where('id', '!=', 1)
                ->get();
            $blocked_users = User::with('invitation')->where('is_active', false)
                ->where('crew_id', $currentUser->crew_id)
                ->where('id', '!=', 1)
                ->get();
        }

        return view('admin.users.show', compact('users', 'blocked_users'));
    }

    public function newUser()
    {
        $user = Auth::user();

        if ($user->role->name === "admin") {
            $roles = Role::all();
        } elseif ($user->role_id == 6) {

            $roles = Role::where("id", 4)->get();
        } else {
            $roles = Role::where("name", "!=", "admin")
                ->where("name", "!=", "Director")
                ->where("name", "!=", "profesor")
                ->get();
        }
        $crews = Crew::all();
        return view('admin.users.new', compact('roles', 'crews'));
    }

    private function getUniqueUsername($username)
    {

        $usernames = User::pluck('username')->toArray();

        $contador = 1;
        $nuevoUsername = $username;

        while (in_array($nuevoUsername, $usernames)) {
            $nuevoUsername = $username . $contador;
            $contador++;
        }

        return $nuevoUsername;
    }

    public function insertUser(UserRequest $request)
    {
        if (in_array((int) $request->role_id, [1, 5])) {
            $request->merge(['crew_id' => 1]);
        }
        $username = $this->getUniqueUsername(explode(' ', trim($request->name))[0]);

        $user = User::create([
            'name' => $request->name,
            'surnames' => $request->surnames,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'crew_id' => $request->crew_id,
            'phone' => $request->phone,
            'cel_phone' => $request->cel_phone,
            'genre' => $request->genre,
            'password' => Hash::make(Str::random(32)),
            'username' => $username
        ]);

        $token = Str::random(64);
        \App\Models\UserInvitation::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addDays(7),
            'used' => false,
        ]);

        $user_mail = $request->email;

        try {
            Mail::to($user_mail)->send(new \App\Mail\UserInvitation($user, $token));
            $success = true;
        } catch (Exception $e) {
            $success = false;
        }

        if ($user) {
            return redirect()->route('admin.users.show');
        } else {
            return redirect()->route('admin.users.new')->with('error', 'error al guardar usuario');
        }
    }

    public function editUser($id)
    {
        $user = User::find($id);
        $currentUser = Auth::user();

        if (!$user) {
            abort(404);
        }

        if ($currentUser->role_id == 1) {
            $roles = Role::all();
        } elseif ($currentUser->role_id == 2) {
            if ($user->crew_id != $currentUser->crew_id) {
                abort(403, 'No tienes permiso para editar este usuario.');
            }
            $roles = Role::all();
        } else {
            abort(403, 'No tienes permiso para editar usuarios.');
        }
        $crews = Crew::all();

        return view('admin.users.edit', compact('user', 'roles', 'crews'));
    }

    public function updateUser(UserRequest $request, $id)
    {
        $user = User::find($id);
        $currentUser = Auth::user();

        if (!$user) {
            abort(404);
        }

        if ($currentUser->role_id == 2 && $user->crew_id != $currentUser->crew_id) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        if ($currentUser->role_id != 1 && $currentUser->role_id != 2) {
            abort(403, 'No tienes permiso para editar usuarios.');
        }

        $wasUpdated = $user ->update([
            'name' => $request->name,
            'surnames' => $request->surnames,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'crew_id' => $request->crew_id,
            'phone' => $request->phone,
            'cel_phone' => $request->cel_phone,
            'genre' => $request->genre
        ]);

        if ($wasUpdated) {
            return redirect()->route('admin.users.show');
        } else {
            $roles = Role::all();
            $crews = Crew::all();
            return redirect()->route('admin.users.edit', compact('user', 'roles', 'crews'))->with('error', 'No se detectaron cambios en la información del usuario.');
        }
    }

    public function blockUser($id)
    {
        $user = User::find($id);
        $currentUser = Auth::user();

        if (!$user) {
            abort(404);
        }

        if ($currentUser->role_id == 2 && $user->crew_id != $currentUser->crew_id) {
            abort(403, 'No tienes permiso para bloquear este usuario.');
        }

        if ($currentUser->role_id != 1 && $currentUser->role_id != 2) {
            abort(403, 'No tienes permiso para bloquear usuarios.');
        }

        \App\Models\UserInvitation::where('user_id', $user->id)
            ->where('used', false)
            ->delete();

        $user->update([
            'is_active' => false
        ]);

        return redirect()->route('admin.users.show');
    }

    public function activateUser($id)
    {
        $user = User::find($id);
        $currentUser = Auth::user();

        if (!$user) {
            abort(404);
        }

        if ($currentUser->role_id == 2 && $user->crew_id != $currentUser->crew_id) {
            abort(403, 'No tienes permiso para activar este usuario.');
        }

        if ($currentUser->role_id != 1 && $currentUser->role_id != 2) {
            abort(403, 'No tienes permiso para activar usuarios.');
        }

        $user->update([
            'is_active' => true
        ]);

        $hasAcceptedInvitation = \App\Models\UserInvitation::where('user_id', $user->id)
            ->where('used', true)
            ->exists();

        if (!$hasAcceptedInvitation) {
            $token = Str::random(64);
            \App\Models\UserInvitation::create([
                'user_id' => $user->id,
                'token' => $token,
                'expires_at' => now()->addDays(7),
                'used' => false,
            ]);

            try {
                Mail::to($user->email)->send(new \App\Mail\UserInvitation($user, $token));
            } catch (Exception) {
            }
        }

        return redirect()->route('admin.users.show');
    }

    public function resendInvitation($id)
    {
        $user = User::find($id);
        $currentUser = Auth::user();

        if (!$user) {
            abort(404);
        }

        if ($currentUser->role_id == 2 && $user->crew_id != $currentUser->crew_id) {
            abort(403, 'No tienes permiso para reenviar invitaciones de este usuario.');
        }

        if ($currentUser->role_id != 1 && $currentUser->role_id != 2 && $currentUser->role_id != 6) {
            abort(403, 'No tienes permiso para reenviar invitaciones.');
        }

        \App\Models\UserInvitation::where('user_id', $user->id)
            ->where('used', false)
            ->delete();

        $token = Str::random(64);
        \App\Models\UserInvitation::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addDays(7),
            'used' => false,
        ]);

        try {
            Mail::to($user->email)->send(new \App\Mail\UserInvitation($user, $token));
            return redirect()->route('admin.users.show')->with('success', 'Invitación reenviada exitosamente');
        } catch (Exception) {
            return redirect()->route('admin.users.show')->with('error', 'Error al enviar el correo de invitación');
        }
    }
}

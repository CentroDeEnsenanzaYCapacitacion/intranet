<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\NewUser;
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
    public function getUsers()
    {
        $users = User::all()->where('is_active', true)->skip(1);
        $blocked_users = User::where('is_active', false)->get();
        return view('admin.users.show', compact('users', 'blocked_users'));
    }

    public function newUser()
    {
        $user = Auth::user();

        if ($user->role->name === "admin") {

            $roles = Role::all();
        } else {
            $roles = Role::where("name", "!=", "admin")->where("name", "!=", "Director")->get();
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

        $password = Str::random(12);

        $user = User::create([
            'name' => $request->name,
            'surnames' => $request->surnames,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'crew_id' => $request->crew_id,
            'phone' => $request->phone,
            'cel_phone' => $request->cel_phone,
            'genre' => $request->genre,
            'password' => Hash::make($password),
            'username' => $username
        ]);

        $user_mail = $request->email;

        try {
            Mail::to($user_mail)->send(new NewUser($user, $password));
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
        $roles = Role::all();
        $crews = Crew::all();

        return view('admin.users.edit', compact('user', 'roles', 'crews'));
    }

    public function updateUser(UserRequest $request, $id)
    {
        $user = User::find($id);
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
        $user->update([
            'is_active' => false
        ]);

        return redirect()->route('admin.users.show');
    }

    public function activateUser($id)
    {
        $user = User::find($id);
        $user->update([
            'is_active' => true
        ]);

        return redirect()->route('admin.users.show');
    }
}

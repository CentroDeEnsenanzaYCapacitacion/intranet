<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Crew;

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
        $roles = Role::all();
        $crews = Crew::all();
        return view('admin.users.new', compact('roles', 'crews'));
    }

    public function insertUser(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'surnames' => $request->surnames,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'crew_id' => $request->crew_id,
            'phone' => $request->phone,
            'cel_phone' => $request->cel_phone,
            'genre' => $request->genre
        ]);

        if($user) {
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

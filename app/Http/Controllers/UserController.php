<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Crew;

class UserController extends Controller
{
    public function getUsers(){
        $users = User::all()->skip(1);
        return view('admin.users.show',compact('users'));
    }

    public function newUser(){
        $roles = Role::all();
        $crews = Crew::all();
        return view('admin.users.new',compact('roles','crews'));
    }

    public function insertUser(UserRequest $request){
        $user=User::create([
            'name' => $request->name,
            'surnames' => $request->surnames,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'crew_id' => $request->crew_id,
            'phone' => $request->phone,
            'cel_phone' => $request->cel_phone,
            'genre' => $request->genre
        ]);

        if($user){
            return redirect()->route('admin.users.show');
        }else{
            return redirect()->route('admin.users.new')->with('error','caca');
        }
    }
}

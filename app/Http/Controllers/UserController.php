<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getUsers(){
        $users = User::all()->skip(1);
        return view('admin.users.show',compact('users'));
    }
}

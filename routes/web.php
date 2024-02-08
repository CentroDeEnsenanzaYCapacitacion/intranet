<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Session;


Route::post('/heartbeat', function () {
    if (Session::has('active_session')) {
        Session::put('last_heartbeat', time());
    }
    return response()->json(['status' => 'success']);
})->name('heartbeat');

Route::get('/', [LoginController::class,'login'])->name('login');
Route::post('/', [LoginController::class,'attemptLogin'])->name('attemptLogin');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/admin/functions', function(){
        return view('admin.menu');
    })->name('adminFunctions');
    
    Route::get('/admin/users', [UserController::class,'getUsers'])->name('admin.users.show');
});


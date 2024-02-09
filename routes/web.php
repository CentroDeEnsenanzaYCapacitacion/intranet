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

Route::get('/logout', [LoginController::class,'logout'])->name('logout');
Route::get('/', [LoginController::class,'login'])->name('login');
Route::post('/', [LoginController::class,'attemptLogin'])->name('attemptLogin');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/admin/functions', function () {
        return view('admin.menu');
    })->name('adminFunctions');

    Route::get('/admin/users', [UserController::class,'getUsers'])->name('admin.users.show');
    Route::get('/admin/user/new', [UserController::class,'newUser'])->name('admin.users.new');
    Route::get('/admin/user/edit/{id}', [UserController::class,'editUser'])->name('admin.users.edit');
    Route::post('/admin/user/insert', [UserController::class,'insertUser'])->name('admin.users.insert');
    Route::put('/admin/user/update/{id}', [UserController::class,'updateUser'])->name('admin.users.update');
    Route::delete('/admin/user/delete/{id}', [UserController::class,'deleteUser'])->name('admin.users.delete');
});
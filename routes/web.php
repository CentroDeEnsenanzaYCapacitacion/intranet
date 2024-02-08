<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Session;

Route::get('/',[LoginController::class,'login'])->name('login'); 
Route::post('/',[LoginController::class,'attemptLogin'])->name('attemptLogin');
Route::middleware(['auth', 'checkInactivity'])->group(function(){
    Route::get('/dashboard',function(){
        return view('dashboard');
    })->name('dashboard'); 
});

Route::post('/heartbeat', function () {
    if (Session::has('active_session')) {
        Session::put('last_heartbeat', time());
    }
    return response()->json(['status' => 'success']);
})->name('heartbeat');


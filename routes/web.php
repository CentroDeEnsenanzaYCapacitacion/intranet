<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
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

Route::get('/friend/{friendId}', function ($friendId) {
    return view('deeplinks.friend', ['friendId' => $friendId]);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/admin/functions', function () {
        return view('admin.menu');
    })->name('adminFunctions');

    Route::get('/system/main', function () {
        return view('system.main');
    })->name('system.main');

    Route::get('/admin/stats_menu', function () {
        return view('admin.stats.menu');
    })->name('admin.stats.menu');

    Route::get('/admin/rosters_menu', function () {
        return view('admin.rosters.menu');
    })->name('admin.rosters.menu');

    require 'admin_users.php';
    require 'admin_stats.php';
    require 'admin_rosters.php';
    require 'admin_catalogues_courses.php';
    require 'admin_catalogues_amounts.php';
    require 'admin_catalogues_perceptions.php';
    require 'admin_requests.php';
    require 'system_reports.php';
    require 'system_students.php';
    require 'system_collection.php';
    require 'system_calendars.php';
    require 'web_admin.php';
    require 'tickets.php';
});

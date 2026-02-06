<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\SetPasswordController;
use App\Http\Controllers\ConfirmPasswordController;
use App\Http\Controllers\SystemCalendarController;
use Illuminate\Support\Facades\Artisan;

Route::get('/logout', [LoginController::class,'logout'])->name('logout');
Route::get('/', [LoginController::class,'login'])->name('login');
Route::post('/', [LoginController::class,'attemptLogin'])->middleware('throttle:5,1')->name('attemptLogin');

Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->middleware('throttle:3,1')->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

Route::get('/set-password', [SetPasswordController::class, 'showSetPasswordForm'])->name('set-password');
Route::post('/set-password', [SetPasswordController::class, 'setPassword'])->name('set-password.post');

Route::get('/internal/clear-cache', function () {
    $token = request()->query('token');
    $serverToken = env('CACHE_CLEAR_TOKEN');

    if (!$serverToken || !hash_equals($serverToken, (string) $token)) {
        abort(403, 'Forbidden');
    }

    Artisan::call('optimize:clear');

    return 'Laravel cache cleared';
})->middleware('throttle:10,1');

Route::get('/.well-known/apple-app-site-association', function () {
    $path = public_path('.well-known/apple-app-site-association');

    if (!file_exists($path)) {
        abort(404, 'Archivo no encontrado');
    }

    return response()->file($path, [
        'Content-Type' => 'application/json',
    ]);
});

Route::get('/.well-known/assetlinks.json', function () {
    $path = public_path('.well-known/assetlinks.json');

    if (!file_exists($path)) {
        abort(404, 'Archivo no encontrado');
    }

    return response()->file($path, [
        'Content-Type' => 'application/json',
    ]);
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
    Route::get('/system/calendars/menu', function () {
        return view('system.calendar.menu');
    })->name('system.calendars.menu')->middleware('role:1,2');

    Route::get('/system/calendars/eub', [SystemCalendarController::class, 'eub'])
        ->name('system.calendars.eub');
    Route::post('/system/calendars/eub/{student_id}', [SystemCalendarController::class, 'updateEub'])
        ->name('system.calendars.eub.update');


    Route::get('/system/grades/menu', function () {
        return view('system.grades.menu');
    })->name('system.grades.menu')->middleware('role:1,2,7');

    Route::get('/admin/stats_menu', function () {
        return view('admin.stats.menu');
    })->name('admin.stats.menu');

    Route::get('/admin/rosters_menu', function () {
        return view('admin.rosters.menu');
    })->name('admin.rosters.menu');

    Route::get('/change-password', [ChangePasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('password.change.update');

    Route::get('/confirm-password', [ConfirmPasswordController::class, 'show'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmPasswordController::class, 'confirm'])->name('password.confirm.store');
    Route::post('/confirm-password/ajax', [ConfirmPasswordController::class, 'confirmAjax'])->name('password.confirm.ajax');

    require 'admin_users.php';
    require 'admin_stats.php';
    require 'admin_rosters.php';
    require 'admin_catalogues_courses.php';
    require 'admin_catalogues_amounts.php';
    require 'admin_catalogues_perceptions.php';
    require 'admin_catalogues_questions.php';
    require 'admin_requests.php';
    require 'system_reports.php';
    require 'system_students.php';
    require 'system_collection.php';
    require 'system_calendars.php';
    require 'web_admin.php';
    require 'tickets.php';
});

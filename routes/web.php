<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

Route::post('/heartbeat', function () {
    if (Session::has('active_session')) {
        Session::put('last_heartbeat', time());
    }
    return response()->json(['status' => 'success']);
})->name('heartbeat');

Route::get('/logout', [LoginController::class,'logout'])->name('logout');
Route::get('/', [LoginController::class,'login'])->name('login');
Route::post('/', [LoginController::class,'attemptLogin'])->name('attemptLogin');

Route::get('/friend/{friendId}', function (Request $request, $friendId) {
    return view('deeplinks.friend', ['friendId' => $friendId]);
});

// Route::get('/friend/{friendId}', function (Request $request, $friendId) {
//     $userAgent = $request->header('User-Agent');

//     $deeplink = "rico-guide://friend/$friendId";
//     $fallback = "https://play.google.com/store/apps/details?id=com.tuempresa.ricoapp";

//     // Si es Android o iOS, intentamos redirigir a la app
//     if (str_contains($userAgent, 'Android') || str_contains($userAgent, 'iPhone')) {
//         return redirect()->away($deeplink);
//     }

//     // Si es otro tipo de navegador, redirige a tienda o web
//     return redirect($fallback);
// });


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

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StatsController;
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

    #User
    Route::get('/admin/users', [UserController::class,'getUsers'])->name('admin.users.show');
    Route::get('/admin/user/new', [UserController::class,'newUser'])->name('admin.users.new');
    Route::get('/admin/user/edit/{id}', [UserController::class,'editUser'])->name('admin.users.edit');
    Route::post('/admin/user/insert', [UserController::class,'insertUser'])->name('admin.users.insert');
    Route::put('/admin/user/update/{id}', [UserController::class,'updateUser'])->name('admin.users.update');
    Route::delete('/admin/user/block/{id}', [UserController::class,'blockUser'])->name('admin.users.block');

    #Catalogues
    Route::get('/admin/catalogues_menu', function () {
        return view('admin.catalogues.menu');
    })->name('admin.catalogues.menu');

    Route::get('/admin/catalogues/courses', [CourseController::class,'getCourses'])->name('admin.catalogues.courses.show');
    Route::get('/admin/catalogues/course/new', [CourseController::class,'newCourse'])->name('admin.catalogues.courses.new');
    Route::get('/admin/catalogues/course/edit/{id}', [CourseController::class,'editCourse'])->name('admin.catalogues.courses.edit');
    Route::post('/admin/catalogues/course/insert', [CourseController::class,'insertCourse'])->name('admin.catalogues.courses.insert');
    Route::put('/admin/catalogues/course/update/{id}', [CourseController::class,'updateCourse'])->name('admin.catalogues.courses.update');
    Route::delete('/admin/catalogues/course/delete/{id}', [CourseController::class,'deleteCourse'])->name('admin.catalogues.courses.delete');

    #Stats
    Route::get('/admin/stats_menu', function () {
        return view('admin.stats.menu');
    })->name('admin.stats.menu');

    Route::get('/admin/stats/reports/{period}', [StatsController::class,'reports'])->name('admin.stats.reports');
});

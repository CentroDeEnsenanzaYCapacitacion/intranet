<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/system/student/search', [StudentController::class,'search'])->name('system.students.search');
Route::post('/system/student/search', [StudentController::class,'searchPost'])->name('system.students.search-post');
Route::get('/system/student/profile/{student_id}', [StudentController::class,'profile'])->name('system.student.profile');
Route::post('/system/student/update', [StudentController::class,'update'])->name('system.student.update');

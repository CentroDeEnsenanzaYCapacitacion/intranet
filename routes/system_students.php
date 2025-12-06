<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/system/student/search', [StudentController::class,'search'])->name('system.students.search');
Route::post('/system/student/search', [StudentController::class,'searchPost'])->name('system.students.search-post');
Route::get('/system/student/profile/{student_id}', [StudentController::class,'profile'])->name('system.student.profile');
Route::post('/system/student/update', [StudentController::class,'update'])->name('system.student.update');
Route::post('/system/student/upload-profile-image/{student_id}', [StudentController::class, 'upload_profile_image'])->name('system.student.upload-profile-image');
Route::get('/system/student/profile-image/{student_id}', [StudentController::class, 'profile_image'])->name('system.student.profile-image');
Route::get('/system/student/image/{student_id}', [StudentController::class, 'get_image'])->name('system.student.image');
Route::post('/system/student/save-form-data/{student_id}', [StudentController::class, 'saveFormData'])->name('system.student.save-form-data');
Route::post('/system/student/request-tuition-change', [StudentController::class, 'requestTuitionChange'])->name('system.student.request-tuition-change');
Route::post('/system/student/add-observation', [StudentController::class, 'addObservation'])->name('system.student.add-observation');
Route::post('/system/student/upload-document', [StudentController::class, 'uploadDocument'])->name('system.student.upload-document');

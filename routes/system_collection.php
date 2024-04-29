<?php

use App\Http\Controllers\CollectionController;
use Illuminate\Support\Facades\Route;

Route::get('/system/collection/menu', [CollectionController::class,'getMenu'])->name('system.collection.menu');
Route::get('/system/collection/tuition', [CollectionController::class,'show'])->name('system.collection.tuition');
Route::get('/system/collection/{student_id}/tuitions', [CollectionController::class,'getStudentTuitions'])->name('system.collection.student.tuitions');
Route::post('/system/collection/tuition/search', [CollectionController::class,'searchPost'])->name('system.collection.tuitions.search-post');
Route::get('/system/collection/{student_id}/newtuition', [CollectionController::class,'newTuition'])->name('system.collection.student.newtuition');
Route::post('/system/collection/tuition/insert', [CollectionController::class,'receiptPost'])->name('system.collection.tuitions.receipt-post');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AmountController;

Route::get('/admin/catalogues/amounts', [AmountController::class,'getAmounts'])->name('admin.catalogues.amounts.show');
Route::get('/admin/catalogues/amounts/generate', [AmountController::class,'generateAmounts'])->name('admin.catalogues.amounts.generate');
Route::get('/admin/catalogues/amounts/clean', [AmountController::class,'cleanAmounts'])->name('admin.catalogues.amounts.clean');
Route::get('/admin/catalogues/amount/edit/{id}', [AmountController::class,'editAmount'])->name('admin.catalogues.amount.edit');
// Route::post('/admin/catalogues/course/insert', [CourseController::class,'insertCourse'])->name('admin.catalogues.courses.insert');
Route::put('/admin/catalogues/amount/update/{id}', [AmountController::class,'updateAmount'])->name('admin.catalogues.amount.update');
//Route::delete('/admin/catalogues/course/delete/{id}', [CourseController::class,'deleteCourse'])->name('admin.catalogues.courses.delete');

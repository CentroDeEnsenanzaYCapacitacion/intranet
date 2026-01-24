<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AmountController;

Route::get('/admin/catalogues/amounts', [AmountController::class,'getAmounts'])->name('admin.catalogues.amounts.show');
Route::get('/admin/catalogues/amounts/generate', [AmountController::class,'generateAmounts'])->name('admin.catalogues.amounts.generate')->middleware('password.confirm');
Route::get('/admin/catalogues/amounts/clean', [AmountController::class,'cleanAmounts'])->name('admin.catalogues.amounts.clean')->middleware('password.confirm');
Route::get('/admin/catalogues/amount/edit/{id}', [AmountController::class,'editAmount'])->name('admin.catalogues.amount.edit');

Route::put('/admin/catalogues/amount/update/{id}', [AmountController::class,'updateAmount'])->name('admin.catalogues.amount.update')->middleware('password.confirm');

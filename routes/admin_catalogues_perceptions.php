<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerceptionsController;

Route::get('/admin/catalogues/defs', [PerceptionsController::class, 'getData'])->name('admin.catalogues.perceptions.show');
Route::post('/admin/catalogues/defs', [PerceptionsController::class, 'store'])->name('admin.catalogues.perceptions.store');
Route::delete('/admin/catalogues/defs/{id}', [PerceptionsController::class, 'destroy'])->name('admin.catalogues.perceptions.destroy');

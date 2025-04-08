<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RosterController;

Route::get('/admin/staff/', [RosterController::class,'index'])->name('admin.staff.show');
Route::get('/admin/staff/create', [RosterController::class, 'create'])->name('admin.staff.create');
Route::post('/admin/staff/store', [RosterController::class, 'store'])->name('admin.staff.store');
Route::patch('/admin/staff/{id}/deactivate', [RosterController::class, 'deactivate'])->name('admin.staff.deactivate');
Route::get('/admin/staff/{id}/edit', [RosterController::class, 'edit'])->name('admin.staff.edit');
Route::put('/admin/staff/{id}', [RosterController::class, 'update'])->name('admin.staff.update');


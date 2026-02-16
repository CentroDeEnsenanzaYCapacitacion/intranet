<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RosterController;
use App\Http\Controllers\PerceptionsController;

Route::get('/admin/staff/', [RosterController::class,'index'])->name('admin.staff.show');
Route::get('/admin/staff/create', [RosterController::class, 'create'])->name('admin.staff.create');
Route::post('/admin/staff/store', [RosterController::class, 'store'])->name('admin.staff.store');
Route::patch('/admin/staff/{id}/deactivate', [RosterController::class, 'deactivate'])->name('admin.staff.deactivate');
Route::get('/admin/staff/{id}/edit', [RosterController::class, 'edit'])->name('admin.staff.edit');
Route::put('/admin/staff/{id}', [RosterController::class, 'update'])->name('admin.staff.update');
Route::get('/admin/rosters/', [RosterController::class,'rosters'])->name('admin.rosters.panel');
Route::post('/admin/staff-adjustments', [RosterController::class, 'storeAdjustment'])->name('admin.staff.adjustments.store');
Route::delete('/admin/staff-adjustments/{id}', [RosterController::class, 'destroyAdjustment'])->name('admin.staff.adjustments.destroy');
Route::get('/admin/rosters/payroll-report', [RosterController::class, 'payrollReport'])->name('admin.rosters.payroll.report');

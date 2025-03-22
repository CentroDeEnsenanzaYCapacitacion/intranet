<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\BillingController;

Route::get('/admin/stats/reports/{period}', [StatsController::class,'reports'])->name('admin.stats.reports');
Route::get('/admin/stats/billing/', [BillingController::class,'index'])->name('admin.stats.billing');


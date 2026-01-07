<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\BillingController;
use App\Models\Report;

Route::get('/admin/stats/reports', function() {
    $latestReport = Report::orderBy('created_at', 'desc')->first();
    $defaultYear = $latestReport ? \Carbon\Carbon::parse($latestReport->created_at)->year : \Carbon\Carbon::now()->year;
    return redirect()->route('admin.stats.reports', ['period' => 'anual', 'year' => $defaultYear]);
});

Route::get('/admin/stats/reports/{period}/{year?}', [StatsController::class,'reports'])->name('admin.stats.reports');
Route::get('/admin/stats/billing/', [BillingController::class,'index'])->name('admin.stats.billing');


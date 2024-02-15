<?php
    use Illuminate\Support\Facades\Route;   
    use App\Http\Controllers\StatsController;

    Route::get('/admin/stats/reports/{period}', [StatsController::class,'reports'])->name('admin.stats.reports');
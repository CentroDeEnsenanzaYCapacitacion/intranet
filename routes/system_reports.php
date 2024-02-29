<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/system/reports', [ReportController::class,'getReports'])->name('system.reports.show');
Route::get('/system/report/new', [ReportController::class,'newReport'])->name('system.report.new');
Route::post('/system/report/insert', [ReportController::class,'insertReport'])->name('system.report.insert');
Route::get('/system/report/signdiscount/{report_id}', [ReportController::class,'signDiscount'])->name('system.reports.signdiscount');
Route::get('/system/report/receipt_confirmation/{report_id}', [ReportController::class,'receiptConfirmation'])->name('system.reports.receiptconfirmation');
Route::post('/system/report/receipt', [ReportController::class,'generateReceipt'])->name('system.report.generatereceipt');
Route::post('/system/report/receiptorrequest', [ReportController::class,'receiptOrRequest'])->name('system.report.receiptorrequest');
Route::get('/system/report/update/{report_id}', [ReportController::class,'updateReport'])->name('system.reports.update');

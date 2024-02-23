<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/system/reports', [ReportController::class,'getReports'])->name('system.reports.show');
Route::get('/system/report/new', [ReportController::class,'newReport'])->name('system.report.new');
Route::post('/system/report/insert', [ReportController::class,'insertReport'])->name('system.report.insert');
Route::get('/system/report/signdiscount/{report_id}', [ReportController::class,'signDiscount'])->name('system.reports.signdiscount');
Route::post('/system/report/receiptorrequest', [ReportController::class,'receiptOrRequest'])->name('system.report.receiptorrequest');
Route::get('/printrecipe', [PdfController::class, 'generateReceipt'])->name('pdf.receipt');

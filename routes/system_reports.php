<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/system/reports', [ReportController::class,'getReports'])->name('system.reports.show');
Route::get('/system/report/new', [ReportController::class,'newReport'])->name('system.report.new');
Route::post('/system/report/insert', [ReportController::class,'insertReport'])->name('system.report.insert')->middleware('password.confirm');
Route::get('/system/report/setamount/{report_id}', [ReportController::class,'signDiscount'])->name('system.reports.setamount');
Route::post('/system/report/receiptorrequest', [ReportController::class,'receiptOrRequest'])->name('system.report.receiptorrequest')->middleware('password.confirm');
Route::get('/system/report/update/{report_id}', [ReportController::class,'updateReport'])->name('system.reports.update')->middleware('password.confirm');
Route::post('/system/validate-amount', [ReportController::class,'validateAmount'])->name('system.report.validateAmount')->middleware('password.confirm');
Route::get('/system/get-inscription-amount/{course_id}', [ReportController::class,'getInscriptionAmount'])->name('system.report.getInscriptionAmount');

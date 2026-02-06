<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestController;

Route::get('/admin/requests', [RequestController::class,'getRequests'])->name('admin.requests.show');
Route::get('/admin/request/{request_id}', [RequestController::class,'editRequest'])->name('admin.requests.edit');
Route::post('/admin/request/{request_id}', [RequestController::class,'changePercentage'])->name('admin.requests.changePercentage');
Route::post('/admin/request/{request_id}/tuition', [RequestController::class,'changeTuition'])->name('admin.requests.changeTuition');
Route::get('/admin/request/{request_id}/{action}', [RequestController::class,'updateRequest'])->name('admin.request.update');

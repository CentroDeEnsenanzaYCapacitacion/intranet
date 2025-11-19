<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/tickets', [TicketController::class, 'list'])->name('tickets.list');
Route::get('/tickets/new', [TicketController::class, 'form'])->name('tickets.form');
Route::post('/tickets/save', [TicketController::class, 'save'])->name('tickets.save');
Route::get('/tickets/{id}', [TicketController::class, 'detail'])->name('tickets.detail');
Route::post('/tickets/{ticket}/message', [TicketController::class, 'storeMessage'])->name('tickets.message');
Route::put('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
Route::get('/tickets/image/{filename}', [TicketController::class, 'getImage'])->name('tickets.image');
Route::get('/tickets/attachment/{filename}', [TicketController::class, 'getAttachment'])->name('tickets.attachment');

<?php

use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/schedule/', [CalendarController::class, 'calendar'])->name('admin.schedule.calendar');
Route::post('/admin/hour-assignments/store', [CalendarController::class, 'storeHourAssignment'])->name('hourAssignments.store');
Route::get('/admin/hour-assignments/events', [CalendarController::class, 'getHourAssignments'])->name('hourAssignments.events');
Route::put('/admin/hour-assignments/{id}', [CalendarController::class, 'updateHourAssignment']);
Route::delete('/admin/hour-assignments/{id}', [CalendarController::class, 'deleteHourAssignment']);


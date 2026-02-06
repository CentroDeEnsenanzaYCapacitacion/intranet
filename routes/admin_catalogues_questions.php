<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;

Route::get('/admin/catalogues/questions', [QuestionController::class, 'getQuestions'])->name('admin.catalogues.questions.show');
Route::get('/admin/catalogues/question/new', [QuestionController::class, 'newQuestion'])->name('admin.catalogues.questions.new');
Route::get('/admin/catalogues/question/edit/{id}', [QuestionController::class, 'editQuestion'])->name('admin.catalogues.questions.edit');
Route::post('/admin/catalogues/question/insert', [QuestionController::class, 'insertQuestion'])->name('admin.catalogues.questions.insert');
Route::put('/admin/catalogues/question/update/{id}', [QuestionController::class, 'updateQuestion'])->name('admin.catalogues.questions.update');
Route::delete('/admin/catalogues/question/delete/{id}', [QuestionController::class, 'deleteQuestion'])->name('admin.catalogues.questions.delete');
Route::put('/admin/catalogues/question/activate/{id}', [QuestionController::class, 'activateQuestion'])->name('admin.catalogues.questions.activate');

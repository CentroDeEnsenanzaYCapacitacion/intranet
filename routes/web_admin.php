<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;

Route::get('/web/menu', [WebController::class,'webMenu'])->name('web.menu');
Route::get('/web/carousel', [WebController::class,'webCarousel'])->name('web.carousel');
Route::post('/web/carousel/add', [WebController::class,'webCarouselAdd'])->name('web.carousel.add');
Route::post('/web/carousel/{carousel}/delete', [WebController::class,'webCarouselDelete'])->name('web.carousel.delete');
Route::get('/web/mvv', [WebController::class,'webMvv'])->name('web.mvv');
Route::get('/web/opinions', [WebController::class,'webOpinions'])->name('web.opinions');
Route::post('/web/opinions/add', [WebController::class,'webOpinionsAdd'])->name('web.opinions.add');
Route::post('/web/opinions/{opinion}/delete', [WebController::class,'webOpinionsDelete'])->name('web.opinions.delete');
Route::post('/web/mvv', [WebController::class,'webMvvPost'])->name('web.mvv.post');
Route::post('/web/carousel', [WebController::class,'webCarouselPost'])->name('web.carousel.post');
Route::post('/web/opinions', [WebController::class,'webOpinionsPost'])->name('web.opinions.post');

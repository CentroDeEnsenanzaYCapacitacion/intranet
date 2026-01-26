<?php
        use Illuminate\Support\Facades\Route;
        use App\Http\Controllers\CourseController;

        Route::get('/admin/catalogues_menu', function () {
            return view('admin.catalogues.menu');
        })->name('admin.catalogues.menu');

        Route::get('/admin/catalogues/courses', [CourseController::class,'getCourses'])->name('admin.catalogues.courses.show');
        Route::get('/admin/catalogues/course/new', [CourseController::class,'newCourse'])->name('admin.catalogues.courses.new');
        Route::get('/admin/catalogues/course/edit/{id}', [CourseController::class,'editCourse'])->name('admin.catalogues.courses.edit');
        Route::post('/admin/catalogues/course/insert', [CourseController::class,'insertCourse'])->name('admin.catalogues.courses.insert')->middleware('password.confirm');
        Route::put('/admin/catalogues/course/update/{id}', [CourseController::class,'updateCourse'])->name('admin.catalogues.courses.update')->middleware('password.confirm');
        Route::delete('/admin/catalogues/course/delete/{id}', [CourseController::class,'deleteCourse'])->name('admin.catalogues.courses.delete')->middleware('password.confirm');

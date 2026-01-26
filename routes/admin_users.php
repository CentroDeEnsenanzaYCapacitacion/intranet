<?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\UserController;

    Route::get('/admin/users', [UserController::class,'getUsers'])->name('admin.users.show');
    Route::get('/admin/user/new', [UserController::class,'newUser'])->name('admin.users.new');
    Route::get('/admin/user/edit/{id}', [UserController::class,'editUser'])->name('admin.users.edit');
    Route::post('/admin/user/insert', [UserController::class,'insertUser'])->name('admin.users.insert')->middleware('password.confirm');
    Route::put('/admin/user/update/{id}', [UserController::class,'updateUser'])->name('admin.users.update')->middleware('password.confirm');
    Route::delete('/admin/user/block/{id}', [UserController::class,'blockUser'])->name('admin.users.block')->middleware('password.confirm');
    Route::get('/admin/user/activate/{id}', [UserController::class,'activateUser'])->name('admin.users.activate')->middleware('password.confirm');
    Route::post('/admin/user/resend-invitation/{id}', [UserController::class,'resendInvitation'])->name('admin.users.resend-invitation')->middleware('password.confirm');

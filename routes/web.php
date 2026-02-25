<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AktualitatesController;
use App\Http\Controllers\KomentariController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect()->route('aktualitates.index');
});

Route::view('/pagasts', 'pagasts.index')->name('pagasts.index');
Route::view('/history', 'pagasts.history')->name('pagasts.history');

//news
Route::get('/aktualitates', [AktualitatesController::class, 'index'])->name('aktualitates.index');
Route::get('/aktualitates/kategorija/{id}', [AktualitatesController::class, 'category'])->name('aktualitates.category');

Route::middleware(['auth', 'admin'])->group(function () {

    // users list
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');

    // edit/update user
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');

    // delete user
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

    // aktualitates admin
    Route::get('/aktualitates/create', [AktualitatesController::class, 'create'])->name('aktualitates.create');
    Route::post('/aktualitates', [AktualitatesController::class, 'store'])->name('aktualitates.store');
    Route::get('/aktualitates/{id}/edit', [AktualitatesController::class, 'edit'])->name('aktualitates.edit');
    Route::put('/aktualitates/{id}', [AktualitatesController::class, 'update'])->name('aktualitates.update');
    Route::delete('/aktualitates/{id}', [AktualitatesController::class, 'destroy'])->name('aktualitates.destroy');
});

Route::get('/aktualitates/{id}', [AktualitatesController::class, 'show'])->name('aktualitates.show');

//Kommentari
Route::post('/aktualitates/{id}/komentari', [KomentariController::class, 'store'])
    ->middleware('auth')
    ->name('komentari.store');

Route::delete('/komentari/{id}', [KomentariController::class, 'destroy'])
    ->middleware('auth')
    ->name('komentari.destroy');

//Login and register
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

//Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/email', [ProfileController::class, 'updateEmail'])
        ->name('profile.email');
});


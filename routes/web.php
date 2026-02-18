<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AktualitatesController;
use App\Http\Controllers\KomentariController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('aktualitates.index');
});

Route::view('/pagasts', 'pagasts.index')->name('pagasts.index');
Route::view('/history', 'pagasts.history')->name('pagasts.history');

//news
Route::get('/aktualitates', [AktualitatesController::class, 'index'])->name('aktualitates.index');
Route::get('/aktualitates/kategorija/{id}', [AktualitatesController::class, 'category'])->name('aktualitates.category');
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
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update'); // если хочешь редактирование
});
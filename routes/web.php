<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AktualitatesController;
use App\Http\Controllers\KomentariController;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return redirect()->route('aktualitates.index');
});

Route::get('/aktualitates', [AktualitatesController::class, 'index'])->name('aktualitates.index');
Route::get('/aktualitates/kategorija/{id}', [AktualitatesController::class, 'category'])->name('aktualitates.category');
Route::get('/aktualitates/{id}', [AktualitatesController::class, 'show'])->name('aktualitates.show');

Route::view('/pagasts', 'pagasts.index')->name('pagasts.index');
Route::view('/history', 'pagasts.history')->name('pagasts.history');

Route::post('/aktualitates/{id}/komentari', [KomentariController::class, 'store'])
    ->middleware('auth')
    ->name('komentari.store');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

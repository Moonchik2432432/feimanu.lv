<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AktualitatesController;
use App\Http\Controllers\KomentariController;


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

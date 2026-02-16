<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AktualitatesController;

Route::get('/', function () {
    return redirect()->route('aktualitates.index');
});

Route::get('/aktualitates', [AktualitatesController::class, 'index'])->name('aktualitates.index');
Route::get('/aktualitates/kategorija/{id}', [AktualitatesController::class, 'category'])->name('aktualitates.category');
Route::get('/aktualitates/{id}', [AktualitatesController::class, 'show'])->name('aktualitates.show');
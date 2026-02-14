<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DataController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Главная
Route::get('/', function () {
    return view('home');
});

// Контакты
Route::get('/contacts', function () {
    return view('contacts');
});

// =====================
// AUTH
// =====================

// Login page
Route::get('/login', function () {
    return view('login');
})->name('login');

// Register page
Route::get('/register', function () {
    return view('register');
})->name('register');

// Login / Register POST
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [LoginController::class, 'register']);

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');


/*
|--------------------------------------------------------------------------
| Protected Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Admin dashboard page
    Route::get('/data', function () {
        return view('data');
    });

    // =====================
    // LIST
    // =====================

    Route::get('/data/allIeraksti', [DataController::class, 'showAllIeraksti']);
    Route::get('/data/allKategorijas', [DataController::class, 'showAllKategorijas']);
    Route::get('/data/allKomentari', [DataController::class, 'showAllKomentari']);
    Route::get('/data/allLietotaji', [DataController::class, 'showAllLietotaji']);

    // =====================
    // DELETE
    // =====================

    Route::get('/data/all/{id}/deleteIeraksts', [DataController::class, 'deleteIeraksts']);
    Route::get('/data/all/{id}/deleteKategorija', [DataController::class, 'deleteKategorija']);
    Route::get('/data/all/{id}/deleteKomentars', [DataController::class, 'deleteKomentars']);
    Route::get('/data/all/{id}/deleteLietotajs', [DataController::class, 'deleteLietotajs']);

    // =====================
    // CREATE
    // =====================

    Route::get('/data/createIeraksts', [DataController::class, 'createIeraksts']);
    Route::post('/data/newSubmitIeraksts', [DataController::class, 'newSubmitIeraksts']);

    Route::get('/data/createKategorija', [DataController::class, 'createKategorija']);
    Route::post('/data/newSubmitKategorija', [DataController::class, 'newSubmitKategorija']);

    Route::get('/data/createKomentars', [DataController::class, 'createKomentars']);
    Route::post('/data/newSubmitKomentars', [DataController::class, 'newSubmitKomentars']);

    Route::get('/data/createLietotajs', [DataController::class, 'createLietotajs']);
    Route::post('/data/newSubmitLietotajs', [DataController::class, 'newSubmitLietotajs']);
});

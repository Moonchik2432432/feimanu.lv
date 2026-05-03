<?php

// Maršrutu definēšana Laravel sistēmā

use Illuminate\Support\Facades\Route;

// Kontrolieru pieslēgšana
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsLikeController;

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminCommentsController;
use App\Http\Controllers\Admin\AdminBlockReasonController;
use App\Http\Controllers\Admin\AdminGalleryAlbumController;
use App\Http\Controllers\Admin\AdminGalleryImageController;
use App\Http\Controllers\Admin\AdminContactController;

use App\Http\Controllers\GalleryController;

// Galvenā lapa – pāradresācija uz ziņu sarakstu
Route::get('/', function () {
    return redirect()->route('news.index');
});

// Statiskās lapas
Route::view('/pagasts', 'pagasts.index')->name('pagasts.index');
Route::view('/history', 'pagasts.history')->name('pagasts.history');
Route::view('/about', 'pagasts.about')->name('pagasts.about');
Route::view('/sport', 'pagasts.sport')->name('pagasts.sport');
Route::view('/culture', 'pagasts.culture')->name('pagasts.culture');
Route::view('/religia', 'pagasts.religia')->name('pagasts.religia');

// Noteikumu lapa
Route::get('/rules', [RulesController::class, 'index'])->name('rules.index');


// ===== PUBLIKĀ DAĻA – ZIŅAS =====

// Ziņu saraksts
Route::get('/news', [NewsController::class, 'index'])->name('news.index');

// Ziņu filtrēšana pēc kategorijas
Route::get('/news/category/{id}', [NewsController::class, 'category'])->name('news.category');

// Konkrētas ziņas apskate
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');


// ===== LIKE FUNKCIONALITĀTE =====

// Ziņas “patīk” funkcija (tikai autorizētiem lietotājiem)
Route::post('/news/{id}/like', [NewsLikeController::class, 'toggle'])
    ->middleware('auth')
    ->name('news.like');


// ===== KOMENTĀRI =====

// Komentāra pievienošana
Route::post('/news/{id}/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('comments.store');

// Komentāra dzēšana
Route::delete('/comments/{id}', [CommentController::class, 'destroy'])
    ->middleware('auth')
    ->name('comments.destroy');


// ===== AUTENTIFIKĀCIJA =====

// Pieslēgšanās forma
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Pieslēgšanās apstrāde
Route::post('/login', [AuthController::class, 'login']);

// E-pasta verifikācija
Route::get('/verify/{token}', [AuthController::class, 'verify'])->name('verify');

// Izrakstīšanās
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Reģistrācija
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


// ===== LIETOTĀJA PROFILS =====

Route::middleware('auth')->group(function () {

    // Profila apskate
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    // Profila datu atjaunošana
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Paroles maiņa
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // E-pasta maiņa
    Route::post('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.email');
});

// E-pasta maiņas apstiprināšana
Route::get('/profile/email/confirm/{token}', [ProfileController::class, 'confirmEmailChange'])
    ->name('profile.email.confirm');


// ===== GALERIJA =====

// Albumu saraksts
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

// Konkrēta albuma apskate
Route::get('/gallery/{id}', [GalleryController::class, 'show'])->name('gallery.show');


// ===== KONTAKTU FORMA =====

Route::middleware('auth')->group(function () {

    // Ziņojumu saraksts
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts');

    // Arhivētie ziņojumi
    Route::get('/contacts/archive', [ContactController::class, 'archiveList'])
        ->name('contacts.archive.page');

    // Ziņojuma nosūtīšana
    Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');

    // Ziņojuma arhivēšana
    Route::post('/contacts/{id}/archive', [ContactController::class, 'archive'])->name('contacts.archive.store');

    // Ziņojuma atarhivēšana
    Route::post('/contacts/{id}/unarchive', [ContactController::class, 'unarchive'])->name('contacts.unarchive');

    // Ziņojuma dzēšana
    Route::delete('/contacts/{id}/delete', [ContactController::class, 'delete'])->name('contacts.delete');
});


// ===== ADMINISTRĀCIJAS DAĻA =====

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // ===== LIETOTĀJI =====
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    // Lietotāja bloķēšana un atbloķēšana
    Route::post('/users/{user}/block', [AdminUserController::class, 'block'])->name('admin.users.block');
    Route::post('/users/{user}/unblock', [AdminUserController::class, 'unblock'])->name('admin.users.unblock');

    // Bloķēšanas vēsture
    Route::get('/users/{user}/history', [AdminUserController::class, 'history'])->name('admin.users.history');


    // ===== KATEGORIJAS =====
    Route::get('/category', [AdminCategoryController::class, 'index'])->name('admin.category');
    Route::post('/category', [AdminCategoryController::class, 'store'])->name('admin.category.store');
    Route::put('/category/{id}', [AdminCategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/category/{id}', [AdminCategoryController::class, 'destroy'])->name('admin.category.destroy');


    // ===== ZIŅAS =====
    Route::get('/news', [AdminNewsController::class, 'index'])->name('admin.news');
    Route::post('/news', [AdminNewsController::class, 'store'])->name('admin.news.store');
    Route::put('/news/{id}', [AdminNewsController::class, 'update'])->name('admin.news.update');
    Route::delete('/news/{id}', [AdminNewsController::class, 'destroy'])->name('admin.news.destroy');


    // ===== KOMENTĀRI =====
    Route::delete('/comments/{id}', [AdminCommentsController::class, 'destroy'])->name('admin.comments.destroy');


    // ===== BLOĶĒŠANAS IEMESLI =====
    Route::get('/block-reasons', [AdminBlockReasonController::class, 'index'])->name('admin.block_reasons');
    Route::post('/block-reasons', [AdminBlockReasonController::class, 'store'])->name('admin.block_reasons.store');
    Route::put('/block-reasons/{id}', [AdminBlockReasonController::class, 'update'])->name('admin.block_reasons.update');
    Route::delete('/block-reasons/{id}', [AdminBlockReasonController::class, 'destroy'])->name('admin.block_reasons.destroy');


    // ===== GALERIJA =====
    Route::get('/gallery-albums', [AdminGalleryAlbumController::class, 'index'])->name('admin.gallery.albums');
    Route::post('/gallery-albums/store', [AdminGalleryAlbumController::class, 'store'])->name('admin.gallery.albums.store');
    Route::put('/gallery-albums/{id}/update', [AdminGalleryAlbumController::class, 'update'])->name('admin.gallery.albums.update');
    Route::delete('/gallery-albums/{id}/delete', [AdminGalleryAlbumController::class, 'destroy'])->name('admin.gallery.albums.delete');


    // ===== KONTAKTI =====
    Route::get('/contacts', [AdminContactController::class, 'index'])->name('admin.contacts');
    Route::post('/contacts/{id}/reply', [AdminContactController::class, 'reply'])->name('admin.contacts.reply');
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminCommentsController;
use App\Http\Controllers\Admin\AdminBlockReasonController;

Route::get('/', function () {
    return redirect()->route('news.index');
});

Route::view('/pagasts', 'pagasts.index')->name('pagasts.index');
Route::view('/history', 'pagasts.history')->name('pagasts.history');

// PUBLIC NEWS
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/category/{id}', [NewsController::class, 'category'])->name('news.category');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');

// COMMENTS
Route::post('/news/{id}/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('comments.store');

Route::delete('/comments/{id}', [CommentController::class, 'destroy'])
    ->middleware('auth')
    ->name('comments.destroy');

// AUTH
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.email');
});

// ADMIN
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // USERS
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/users/{user}/block', [AdminUserController::class, 'block'])->name('admin.users.block');
    Route::post('/users/{user}/unblock', [AdminUserController::class, 'unblock'])->name('admin.users.unblock');
    Route::get('/users/{user}/history', [AdminUserController::class, 'history'])->name('admin.users.history');

    // CATEGORY
    Route::get('/category', [AdminCategoryController::class, 'index'])->name('admin.category');
    Route::get('/category/create', [AdminCategoryController::class, 'create'])->name('admin.category.create');
    Route::post('/category', [AdminCategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/category/{id}/edit', [AdminCategoryController::class, 'edit'])->name('admin.category.edit');
    Route::put('/category/{id}', [AdminCategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/category/{id}', [AdminCategoryController::class, 'destroy'])->name('admin.category.destroy');

    // NEWS
    Route::get('/news', [AdminNewsController::class, 'index'])->name('admin.news');
    Route::get('/news/create', [AdminNewsController::class, 'create'])->name('admin.news.create');
    Route::post('/news', [AdminNewsController::class, 'store'])->name('admin.news.store');
    Route::get('/news/{id}/edit', [AdminNewsController::class, 'edit'])->name('admin.news.edit');
    Route::put('/news/{id}', [AdminNewsController::class, 'update'])->name('admin.news.update');
    Route::delete('/news/{id}', [AdminNewsController::class, 'destroy'])->name('admin.news.destroy');

    // COMMENT
    Route::delete('/comments/{id}', [AdminCommentsController::class, 'destroy'])->name('admin.comments.destroy');

    // BLOCK REASONS
    Route::get('/block-reasons', [AdminBlockReasonController::class, 'index'])->name('admin.block_reasons');
    Route::get('/block-reasons/create', [AdminBlockReasonController::class, 'create'])->name('admin.block_reasons.create');
    Route::post('/block-reasons', [AdminBlockReasonController::class, 'store'])->name('admin.block_reasons.store');
    Route::get('/block-reasons/{id}/edit', [AdminBlockReasonController::class, 'edit'])->name('admin.block_reasons.edit');
    Route::put('/block-reasons/{id}', [AdminBlockReasonController::class, 'update'])->name('admin.block_reasons.update');
    Route::delete('/block-reasons/{id}', [AdminBlockReasonController::class, 'destroy'])->name('admin.block_reasons.destroy');
});
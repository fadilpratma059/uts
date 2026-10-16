<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

// Route untuk pengguna yang belum login (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Route yang wajib login (Auth)
Route::middleware('auth')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['create']);
    Route::resource('items', ItemController::class)->only(['store', 'update', 'destroy']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
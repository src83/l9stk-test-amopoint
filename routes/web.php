<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\IndexController;
use Illuminate\Support\Facades\Route;

// Базовый случай
Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('showLoginForm');
Route::post('/login', [LoginController::class, 'login'])->name('login');

// index === login
// Route::get('/', [LoginController::class, 'showLoginForm'])->name('index');
// Route::post('/login', [LoginController::class, 'login'])->name('login');

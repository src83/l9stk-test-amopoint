<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cabinet\IndexController;
use App\Http\Controllers\Cabinet\User\Profile\ProfileController;
use App\Http\Controllers\Cabinet\User\Settings\UserPasswordController;
use Illuminate\Support\Facades\Route;

/**
 * Cabinet area / Кабинет / ЛК
 * http://l9stk.loc/cabinet
 */
Route::get('/', [IndexController::class, 'index'])->name('index');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

/**
 * Настройки юзера
 */
Route::group([
    'prefix' => 'user', 'as' => 'user.',
], static function () {

    Route::group([
        'prefix' => 'profile', 'as' => 'profile.',
    ], static function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
    });

    Route::group([
        'prefix' => 'settings', 'as' => 'settings.',
    ], static function () {
        Route::get('/password', [UserPasswordController::class, 'index'])->name('password.index');
        Route::patch('/password', [UserPasswordController::class, 'update'])->name('password.update');
    });

});

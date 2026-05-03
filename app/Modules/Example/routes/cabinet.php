<?php

use App\Modules\Example\Http\Controllers\Cabinet\Ajax\EntityController;
use App\Modules\Example\Http\Controllers\Cabinet\ExampleController;
use Illuminate\Support\Facades\Route;

/**
 * Example module (placeholder for new features)
 */
Route::group([
    'prefix' => 'example',
], static function () {
    Route::get('/', [ExampleController::class, 'index'])->name('example');
});

/**
 * Ajax-маршруты (placeholder for new features)
 * http://l9stk.loc/cabinet/example/ajax
 */
Route::group([
    'prefix' => 'example/ajax',
], static function () {
    Route::get('/entities', [EntityController::class, 'index'])->name('example.ajax.entities.index');
    Route::post('/entities', [EntityController::class, 'store'])->name('example.ajax.entities.store');
});

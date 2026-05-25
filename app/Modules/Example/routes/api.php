<?php

use App\Modules\Example\Http\Controllers\Api\EventsController;


Route::group([
    'prefix' => 'events',
], static function () {
    Route::get('/', [EventsController::class, 'index'])->name('events.list');
});

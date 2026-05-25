<?php

use App\Modules\Example\Http\Controllers\Api\EventsController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', static fn() => throw new NotFoundHttpException('API root endpoint is not available'));

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
// });

Route::group([
    'prefix' => 'events',
], static function () {
    Route::get('/', [EventsController::class, 'index'])->name('events.list');
});

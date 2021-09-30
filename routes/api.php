<?php

use Illuminate\Support\Facades\Route;
use SertxuDeveloper\Lyra\Http\Controllers;

Route::group(['middleware' => ['web']], function () {

  Route::prefix(config('lyra.routes.api.prefix'))->name(config('lyra.routes.api.name'))->group(function () {

    /** Resources routes */
    Route::get('/resource/{resource}', [Controllers\ResourceController::class, 'index']);
    Route::post('/resource/{resource}', [Controllers\ResourceController::class, 'create']);
    Route::get('/resource/{resource}/{id}', [Controllers\ResourceController::class, 'show']);
    Route::put('/resource/{resource}/{id}', [Controllers\ResourceController::class, 'store']);
    Route::delete('/resource/{resource}/{id}', [Controllers\ResourceController::class, 'delete']);

    /** Cards routes */
    Route::get('/cards/{resource}', [Controllers\CardsController::class, 'index']);
    Route::get('/cards/{resource}/{card}', [Controllers\CardsController::class, 'show']);

    /** Assets routes */
    Route::get('/assets/{any}', [Controllers\AssetsController::class, 'show'])->where('any', '.*');
  });
});

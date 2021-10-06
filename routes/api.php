<?php

use Illuminate\Support\Facades\Route;
use SertxuDeveloper\Lyra\Http\Controllers;

Route::group(['middleware' => ['web']], function () {

  Route::prefix(config('lyra.routes.api.prefix'))->name(config('lyra.routes.api.name'))->group(function () {

    /** Resources routes */
    Route::get('/resource/{resource}', [Controllers\ResourceController::class, 'index'])->name('resource.index');
    Route::post('/resource/{resource}', [Controllers\ResourceController::class, 'create'])->name('resource.create');
    Route::get('/resource/{resource}/{id}', [Controllers\ResourceController::class, 'show'])->name('resource.show');
    Route::put('/resource/{resource}/{id}', [Controllers\ResourceController::class, 'store'])->name('resource.update');
    Route::delete('/resource/{resource}/{id}', [Controllers\ResourceController::class, 'delete'])->name('resource.delete');

    /** Cards routes */
    Route::get('/cards/{resource}', [Controllers\CardsController::class, 'index'])->name('cards.index');
    Route::get('/cards/{resource}/{card}', [Controllers\CardsController::class, 'show'])->name('cards.show');

    /** Assets routes */
    Route::get('/assets/{any}', [Controllers\AssetsController::class, 'show'])->where('any', '.*');
  });
});

<?php

use Illuminate\Support\Facades\Route;
use SertxuDeveloper\Lyra\Http\Controllers;

Route::group(['middleware' => ['api']], function () {

  Route::prefix(config('lyra.routes.api.prefix'))->name(config('lyra.routes.api.name'))->group(function () {

    /**
     * Resources routes
     *
     * index, create, store, show, edit, update, destroy
     * @see https://laravel.com/docs/8.x/controllers#actions-handled-by-resource-controller
     */
    Route::get('/resources/{resource}', [Controllers\ResourceController::class, 'index'])->name('resources.index');
    Route::get('/resources/{resource}/create', [Controllers\ResourceController::class, 'create'])->name('resources.create');
    Route::post('/resources/{resource}', [Controllers\ResourceController::class, 'store'])->name('resources.store');
    Route::get('/resources/{resource}/{id}', [Controllers\ResourceController::class, 'show'])->name('resources.show');
    Route::get('/resources/{resource}/{id}/edit', [Controllers\ResourceController::class, 'edit'])->name('resources.edit');
    Route::post('/resources/{resource}/{id}', [Controllers\ResourceController::class, 'update'])->name('resources.update'); // The PUT method doesn't work with form-data
    Route::delete('/resources/{resource}/{id}', [Controllers\ResourceController::class, 'destroy'])->name('resources.destroy');

    /** Cards routes */
    Route::get('/cards/{resource}', [Controllers\CardsController::class, 'index'])->name('cards.index');
    Route::get('/cards/{resource}/{card}', [Controllers\CardsController::class, 'show'])->name('cards.show');

    /** Assets routes */
    Route::get('/assets/{any}', [Controllers\AssetsController::class, 'show'])->where('any', '.*');
  });
});

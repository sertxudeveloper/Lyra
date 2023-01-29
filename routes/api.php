<?php

use Illuminate\Support\Facades\Route;
use SertxuDeveloper\Lyra\Http\Controllers;

/**
 * Resources routes
 */
Route::get('/resources/{resource}', [Controllers\ResourceController::class, 'index'])
    ->name('resources.index');

Route::get('/resources/{resource}/create', [Controllers\ResourceController::class, 'create'])
    ->name('resources.create');

Route::post('/resources/{resource}', [Controllers\ResourceController::class, 'store'])
    ->name('resources.store');

Route::get('/resources/{resource}/{id}', [Controllers\ResourceController::class, 'show'])
    ->name('resources.show');

Route::get('/resources/{resource}/{id}/edit', [Controllers\ResourceController::class, 'edit'])
    ->name('resources.edit');

Route::post('/resources/{resource}/{id}', [Controllers\ResourceController::class, 'update'])
    ->name('resources.update'); // The PUT method doesn't work with form-data

Route::delete('/resources/{resource}/{id}', [Controllers\ResourceController::class, 'destroy'])
    ->name('resources.destroy');

Route::post('/resources/{resource}/{id}/restore', [Controllers\ResourceController::class, 'restore'])
    ->name('resources.restore');

/**
 * Action routes
 */
//            Route::post('/actions/{resource}', [Controllers\ResourceActionController::class, 'exec'])
//                ->name('resources.action');

/**
 * Cards routes
 */
//            Route::get('/cards/{resource}', [Controllers\CardsController::class, 'index'])
//                ->name('cards.index');
//
//            Route::get('/cards/{resource}/{card}', [Controllers\CardsController::class, 'show'])
//                ->name('cards.show');

/**
 * Assets routes
 */
Route::get('/asset/{path?}', Controllers\AssetsController::class)
    ->where('path', '(.*)')
    ->name('asset');

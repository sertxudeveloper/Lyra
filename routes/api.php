<?php

use Illuminate\Support\Facades\Route;
use SertxuDeveloper\Lyra\Http\Controllers;

Route::group(['middleware' => ['web']], function () {

  Route::prefix(config('lyra.routes.api.prefix'))->name(config('lyra.routes.api.name'))->group(function () {

    Route::get('/assets/{any}', [Controllers\AssetsController::class, 'show'])->where('any', '.*');
  });
});

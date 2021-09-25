<?php

use Illuminate\Support\Facades\Route;
use SertxuDeveloper\Lyra\Http\Controllers;

Route::group(['middleware' => ['web']], function () {

  Route::prefix(config('lyra.routes.web.prefix'))->name(config('lyra.routes.web.name'))->group(function () {

    Route::get('/', [Controllers\MainController::class, 'index'])->name('dashboard');
    Route::get('/{any}', [Controllers\MainController::class, 'index'])->where('any', '.*');
  });
});

<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {
    Route::prefix(config('lyra.routes.web.prefix'))->name(config('lyra.routes.web.name'))->group(function () {
        Route::get('/login', function () {
            echo 'Login page';
        });

        Route::get('/password/reset', function () {
            echo 'Password reset page';
        });
    });
});

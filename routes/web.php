<?php

use SertxuDeveloper\Lyra\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use SertxuDeveloper\Lyra\Http\Controllers\ForgotPasswordController;
use SertxuDeveloper\Lyra\Http\Controllers\LoginController;
use SertxuDeveloper\Lyra\Http\Controllers\MainController;
use SertxuDeveloper\Lyra\Lyra;

Route::group(['middleware' => ['web']], function () {

  Route::prefix(config('lyra.routes.web.prefix'))->name(config('lyra.routes.web.name'))->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Login Routes
    |--------------------------------------------------------------------------
    */
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('showLoginForm');
    Route::post('login', [LoginController::class, 'login'])->name('login');

    /*
    |--------------------------------------------------------------------------
    | Logout Routes
    |--------------------------------------------------------------------------
    */
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('logout', [LoginController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Password Reset Routes
    |--------------------------------------------------------------------------
    */
    if (config('lyra.authenticator') === Lyra::MODE_ADVANCED) {
      Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
      Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
      Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
      Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
    }

    Route::group(['middleware' => 'lyra'], function () {
      Route::get('/', [MainController::class, 'index'])->name('dashboard');
      Route::get('/404', function (){ return redirect(route('lyra.dashboard')); });
      Route::get('/{any}', [MainController::class, 'index'])->where('any', '.*');
    });

  });

});

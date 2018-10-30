<?php

Route::group(['middleware' => ['web']], function () {

  Route::prefix(config('lyra.routes.web.prefix'))->name(config('lyra.routes.web.name'))->group(function () {

    $namespacePrefix = '\\SertxuDeveloper\Lyra\Http\Controllers\\';

    Route::get('logout', $namespacePrefix . 'AuthController@logout')->name('logout');
    Route::get('login', $namespacePrefix . 'AuthController@showLoginForm')->name('showLoginForm');
    Route::post('login', $namespacePrefix . 'AuthController@login')->name('login');

    Route::get('terms', $namespacePrefix . 'MainController@showTerms')->name('terms');
    Route::get('privacy', $namespacePrefix . 'MainController@showPrivacy')->name('privacy');

    Route::group(['middleware' => 'lyra'], function () use ($namespacePrefix) {
      Route::get('/', $namespacePrefix . 'MainController@index')->name('dashboard');
      Route::get('/{any}', $namespacePrefix . 'MainController@index')->where('any', '.*');
    });

  });

});
<?php

Route::group(['middleware' => ['web']], function () {

  Route::prefix(config('lyra.routes.prefix'))->name(config('lyra.routes.name'))->group(function () {

    $namespacePrefix = '\\SertxuDeveloper\Lyra\Http\Controllers\\';

    Route::get('logout', $namespacePrefix . 'AuthController@logout')->name('logout');
    Route::get('login', $namespacePrefix . 'AuthController@showLoginForm')->name('showLoginForm');
    Route::post('login', $namespacePrefix . 'AuthController@login')->name('login');

    Route::get('terms', $namespacePrefix . 'MainController@showTerms')->name('terms');
    Route::get('privacy', $namespacePrefix . 'MainController@showPrivacy')->name('privacy');


    Route::group(['middleware' => 'lyra.user'], function () use ($namespacePrefix) {
      Route::get('/', $namespacePrefix . 'MainController@index')->name('dashboard');
      Route::get('/media', $namespacePrefix . 'MainController@index')->name('media');
      Route::get('/widgets', $namespacePrefix . 'MainController@index')->name('widget');
      Route::get('/users', $namespacePrefix . 'DatatypesController@index')->name('users');
      Route::get('/roles', $namespacePrefix . 'MainController@index')->name('roles');
      Route::get('/menu', $namespacePrefix . 'MainController@menu')->name('menu');
      Route::get('/crud', $namespacePrefix . 'MainController@index')->name('crud');
      Route::get('/settings', $namespacePrefix . 'MainController@index')->name('settings');

      Route::get('/profile', $namespacePrefix . 'MainController@index')->name('profile');

      Route::get('/foo', function() {
        $namespace_resource = Lyra::getResources()[0];
        $namespace_model = $namespace_resource::$model;
        $model = new $namespace_model();
        $resource = new $namespace_resource($model::all());
        dd($resource->fields());
      });

    });

  });

});
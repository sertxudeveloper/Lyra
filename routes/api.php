<?php

Route::group(['middleware' => ['web', 'lyra']], function () {

  Route::prefix(config('lyra.routes.api.prefix'))->name(config('lyra.routes.api.name'))->group(function () {

    $namespacePrefix = '\\SertxuDeveloper\Lyra\Http\Controllers\\';

    Route::get('{resource}', $namespacePrefix . 'DatatypesController@index');
    Route::get('{resource}/create', $namespacePrefix . 'DatatypesController@create');
    Route::post('{resource}', $namespacePrefix . 'DatatypesController@store');
    Route::get('{resource}/{id}', $namespacePrefix . 'DatatypesController@show');
    Route::put('{resource}/{id}', $namespacePrefix . 'DatatypesController@update');
    Route::get('{resource}/{id}/edit', $namespacePrefix . 'DatatypesController@edit');
    Route::delete('{resource}/{id}', $namespacePrefix . 'DatatypesController@destroy');

  });

});
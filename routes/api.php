<?php

Route::group(['middleware' => ['web', 'lyra-api']], function () {

  Route::prefix(config('lyra.routes.api.prefix'))->name(config('lyra.routes.api.name'))->group(function () {

    $namespacePrefix = '\\SertxuDeveloper\Lyra\Http\Controllers\\';

    /* Media Manager routes */
//    Route::get('media', $namespacePrefix . 'MediaManagerController@index');
    Route::get('media/disks', $namespacePrefix . 'MediaManagerController@disks');
    Route::get('media/tree', $namespacePrefix . 'MediaManagerController@tree');
    Route::get('media/files', $namespacePrefix . 'MediaManagerController@files');


    /* Dynamic Resource routes */

    Route::get('{resource}', $namespacePrefix . 'DatatypesController@index');

    Route::get('{resource}/create', $namespacePrefix . 'DatatypesController@create');
    Route::post('{resource}/create', $namespacePrefix . 'DatatypesController@store');

    Route::get('{resource}/{id}', $namespacePrefix . 'DatatypesController@show');

    Route::get('{resource}/{id}/edit', $namespacePrefix . 'DatatypesController@edit');
    Route::post('{resource}/{id}/edit', $namespacePrefix . 'DatatypesController@update');

    Route::delete('{resource}/{id}', $namespacePrefix . 'DatatypesController@destroy');

  });

});

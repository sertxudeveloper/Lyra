<?php

Route::group(['middleware' => ['web', 'lyra-api']], function () {

  Route::prefix(config('lyra.routes.api.prefix'))->name(config('lyra.routes.api.name'))->group(function () {

    $namespacePrefix = '\\SertxuDeveloper\Lyra\Http\Controllers\\';

    /** Media Manager routes */
    Route::get('media/disks', $namespacePrefix . 'MediaManagerController@disks');
    Route::get('media/tree', $namespacePrefix . 'MediaManagerController@tree');
    Route::get('media/files', $namespacePrefix . 'MediaManagerController@files');
    Route::post('media/rename', $namespacePrefix . 'MediaManagerController@rename');
    Route::post('media/move', $namespacePrefix . 'MediaManagerController@move');
    Route::post('media/copy', $namespacePrefix . 'MediaManagerController@copy');
    Route::post('media/delete', $namespacePrefix . 'MediaManagerController@delete');

    /** Dynamic Resource routes */

    /** Create Controller */
    Route::get('{resource}/create', $namespacePrefix . 'CRUD\CreateController@create');
    Route::post('{resource}/create', $namespacePrefix . 'CRUD\CreateController@store');

    /** Show Controller */
    Route::get('{resource}', $namespacePrefix . 'CRUD\ShowController@index');
    Route::get('{resource}/{id}', $namespacePrefix . 'CRUD\ShowController@show');

    /** Edit Controller */
    Route::get('{resource}/{id}/edit', $namespacePrefix . 'CRUD\EditController@edit');
    Route::post('{resource}/{id}/edit', $namespacePrefix . 'CRUD\EditController@update');

    /** Destroy Controller */
    Route::post('{resource}/{id}/delete', $namespacePrefix . 'CRUD\DestroyController@delete');
    Route::post('{resource}/{id}/restore', $namespacePrefix . 'CRUD\DestroyController@restore');
    Route::post('{resource}/{id}/forceDelete', $namespacePrefix . 'CRUD\DestroyController@forceDelete');
  });

});

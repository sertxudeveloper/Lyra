<?php

use Illuminate\Support\Facades\Route;
use SertxuDeveloper\Lyra\Http\Controllers\CRUD\CreateController;
use SertxuDeveloper\Lyra\Http\Controllers\CRUD\DestroyController;
use SertxuDeveloper\Lyra\Http\Controllers\CRUD\EditController;
use SertxuDeveloper\Lyra\Http\Controllers\CRUD\ShowController;
use SertxuDeveloper\Lyra\Http\Controllers\DashboardController;
use SertxuDeveloper\Lyra\Http\Controllers\MediaManagerController;
use SertxuDeveloper\Lyra\Http\Controllers\NotificationsController;
use SertxuDeveloper\Lyra\Http\Controllers\ProfileController;
use SertxuDeveloper\Lyra\Http\Controllers\SearchController;

Route::group(['middleware' => ['web', 'lyra-api']], function () {

  Route::prefix(config('lyra.routes.api.prefix'))->name(config('lyra.routes.api.name'))->group(function () {

    /** Dashboard routes */
    Route::get('/', [DashboardController::class, 'index']);

    /** Media Manager routes */
    Route::get('media/disks', [MediaManagerController::class, 'disks']);
    Route::get('media/tree', [MediaManagerController::class, 'tree']);
    Route::get('media/files', [MediaManagerController::class, 'files']);
    Route::post('media/rename', [MediaManagerController::class, 'rename']);
    Route::post('media/move', [MediaManagerController::class, 'move']);
    Route::post('media/copy', [MediaManagerController::class ,'copy']);
    Route::post('media/delete', [MediaManagerController::class, 'delete']);
    Route::post('media/newFolder', [MediaManagerController::class, 'newFolder']);
    Route::post('media/upload', [MediaManagerController::class, 'upload']);
    Route::post('media/uploadFolder', [MediaManagerController::class, 'uploadFolder']);
    Route::post('media/download', [MediaManagerController::class, 'download']);

    /** Global search routes */
    Route::get('search', [SearchController::class, 'search']);

    Route::get('profile', [ProfileController::class, 'edit']);
    Route::post('profile', [ProfileController::class, 'update']);

    Route::get('notifications', [NotificationsController::class, 'index']);
    Route::get('notifications/{id}', [NotificationsController::class, 'read']);

    /** Dynamic Resource routes */

    /** Create Controller */
    Route::get('{resource}/create', [CreateController::class, 'create']);
    Route::post('{resource}/create', [CreateController::class, 'store']);

    /** Show Controller */
    Route::get('{resource}', [ShowController::class, 'index']);
    Route::get('{resource}/{id}', [ShowController::class, 'show']);

    /** Edit Controller */
    Route::get('{resource}/{id}/edit', [EditController::class, 'edit']);
    Route::post('{resource}/{id}/edit', [EditController::class, 'update']);

    /** Destroy Controller */
    Route::post('{resource}/{id}/delete', [DestroyController::class, 'delete']);
    Route::post('{resource}/{id}/restore', [DestroyController::class, 'restore']);
    Route::post('{resource}/{id}/forceDelete', [DestroyController::class, 'forceDelete']);
  });

});

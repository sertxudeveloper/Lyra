<?php

use Illuminate\Support\Facades\Route;
use SertxuDeveloper\Lyra\Http\Controllers;

Route::get('/{view?}', [Controllers\MainController::class, 'index'])
    ->where('view', '(.*)')
    ->name('index');

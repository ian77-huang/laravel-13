<?php

use App\Http\Controllers\Api\LocaleController;
use App\Http\Controllers\Index\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index']);

// Api
Route::prefix('api')->group(function () {
    Route::post('/lang', [LocaleController::class, 'change']);
});

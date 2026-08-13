<?php

use App\Http\Controllers\Api\LocaleController;
use App\Http\Controllers\Frontend\User\IndexController as UserIndexController;
use App\Http\Controllers\Index\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index']);

// User
Route::prefix('user')->group(function () {
    Route::get('', [UserIndexController::class, 'index'])->middleware('auth');
});

// Api
Route::prefix('api')->group(function () {
    Route::post('/lang', [LocaleController::class, 'change']);
});

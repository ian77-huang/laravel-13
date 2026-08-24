<?php

use App\Http\Controllers\Api\FriendshipsController as ApiFriendshipsController;
use App\Http\Controllers\Api\LocaleController;
use App\Http\Controllers\Api\NotificationsController as ApiNotificationsController;
use App\Http\Controllers\Frontend\User\ChangePasswordController as UserChangePasswordController;
use App\Http\Controllers\Frontend\User\FriendshipsController as UserFriendshipsController;
use App\Http\Controllers\Frontend\User\IndexController as UserIndexController;
use App\Http\Controllers\Frontend\User\ProfileController as UserProfileController;
use App\Http\Controllers\Index\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index']);

// User
Route::prefix('user')->group(function () {
    Route::get('', [UserIndexController::class, 'index'])->middleware('auth');

    Route::get('/change-password', [UserChangePasswordController::class, 'get'])->middleware('auth');

    Route::get('/friends', [UserFriendshipsController::class, 'getIndex'])->middleware('auth');

    Route::get('/profile', [UserProfileController::class, 'get'])->middleware('auth');
    Route::put('/profile', [UserProfileController::class, 'put'])->middleware('auth');
});

// Api
Route::prefix('api')->group(function () {
    Route::post('/lang', [LocaleController::class, 'change']);

    Route::prefix('user/friends')->middleware('auth')->group(function () {
        Route::get('/list', [ApiFriendshipsController::class, 'list']);
        Route::post('/request/{friend}', [ApiFriendshipsController::class, 'sendRequest']);
        Route::post('/accept/{friend}', [ApiFriendshipsController::class, 'acceptRequest']);
        Route::post('/reject/{friend}', [ApiFriendshipsController::class, 'rejectRequest']);
        Route::delete('/remove/{friend}', [ApiFriendshipsController::class, 'removeFriend']);
    });

    Route::prefix('user/notifications')->middleware('auth')->group(function () {
        Route::get('', [ApiNotificationsController::class, 'list']);
        Route::post('/read-all', [ApiNotificationsController::class, 'readAll']);
        Route::post('/{notification}/read', [ApiNotificationsController::class, 'read']);
    });
});

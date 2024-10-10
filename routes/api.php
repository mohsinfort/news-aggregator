<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
    Route::group([
        'prefix' => 'password-reset'
    ], function () {
        Route::post('request', [UserController::class, 'requestPasswordReset'])->name('password.reset');
        Route::put('', [UserController::class, 'updatePassword']);
    });
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'news'
], function() {
    Route::get('', [NewsController::class, 'index']);
    Route::get('user/prefrences', [NewsController::class, 'getNewsListByUserPrefrences']);
    Route::get('{id}', [NewsController::class, 'getNewsById']);
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'user'
], function() {
    Route::post('logout', [UserController::class, 'logout']);

    Route::group([
         'prefix' => 'prefrences'
    ], function () {
        Route::post('', [UserPreferenceController::class, 'updateUserPrefrences']);
    });
});
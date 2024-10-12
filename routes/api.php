<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('register', [UserController::class, 'register']);
    Route::get('email/verify/{id}/{hash}', [UserController::class, 'verifyEmail'])
        ->middleware(['signed'])->name('verification.verify');

    Route::post('login', [UserController::class, 'login']);

    Route::post('forgot-password', [UserController::class, 'requestPasswordReset'])
        ->name('password.email');

    Route::post('reset-password', [UserController::class, 'updatePassword'])
        ->middleware('guest')->name('password.reset');
});
Route::group([
    'middleware' => ['auth:sanctum', 'verified'],
], function() {
    Route::group([
        'prefix' => 'news'
    ], function() {
        Route::get('', [NewsController::class, 'index']);
        Route::get('user/prefrences', [NewsController::class, 'getNewsListByUserPrefrences']);
        Route::get('{id}', [NewsController::class, 'getNewsById']);
    });

    Route::group([
        'prefix' => 'user'
    ], function() {
        Route::post('logout', [UserController::class, 'logout']);

        Route::group([
            'prefix' => 'prefrences'
        ], function () {
            Route::post('', [UserPreferenceController::class, 'updateUserPrefrences']);
        });
    });
});

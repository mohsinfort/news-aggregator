<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
    Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
    Route::group([
        'prefix' => 'password-reset'
    ], function () {
        Route::get('request', [UserController::class, 'requestPasswordReset'])->name('password.reset');
        Route::put('', [UserController::class, 'updatePassword']);
    });
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'news'
], function() {
    Route::get('', [NewsController::class, 'index']);
    Route::get('{id}', [NewsController::class, 'getNewsById']);
});
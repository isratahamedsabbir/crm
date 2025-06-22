<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\Backend\ClientController;
use App\Http\Controllers\Api\Backend\CategoryController;
use App\Http\Controllers\Api\Backend\TypeController;
use App\Models\Category;
use App\Models\Type;
use Illuminate\Support\Facades\Route;

/*
# Auth Route
*/

Route::group(['middleware' => 'guest:api'], function ($router) {
    //register
    Route::post('register', [RegisterController::class, 'register']);
    //login
    Route::post('login', [LoginController::class, 'login'])->name('api.login');
});

Route::group(['middleware' => ['auth:api']], function ($router) {
    Route::post('/logout', [LogoutController::class, 'logout']);
    Route::get('/me', [UserController::class, 'me']);
    Route::delete('/delete-profile', [UserController::class, 'destroy']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/types', [TypeController::class, 'index']);

    Route::controller(ClientController::class)->prefix('clients')->name('clients.')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
        Route::get('/{id}/interactions', 'interactionsIndex');
        Route::post('/{id}/interactions', 'interactionsStore');
    });

});
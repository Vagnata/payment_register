<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return trans('messages.home.welcome');
});

Route::group(['namespace' => 'V1', 'prefix' => 'v1'], function()
{
    Route::prefix('user')->group(function () {
        Route::post('/', 'UserController@post');
    });

    Route::prefix('wallet')->group(function () {
        Route::put('/', 'WalletController@put');
    });

    Route::prefix('transaction')->group(function () {
        Route::put('/', 'TransactionController@post');
    });
});


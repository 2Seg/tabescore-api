<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('ping', 'PingController@ping')->name('ping');

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', 'ProductController@getScore')->name('score');
});
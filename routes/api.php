<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function() {
    Route::post('register', 'Auth\AccessTokenController@register');
    Route::post('login', 'Auth\AccessTokenController@login');
//    Route::put('password', 'Auth\AccessTokenController@changePassword')->middleware('auth:api');
//    Route::get('check', 'Auth\AccessTokenController@check')->middleware('auth:api');
});

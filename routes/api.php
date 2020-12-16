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

Route::middleware('auth:api')->get('/user', 'UserController@AuthRouteAPI');

Route::any('wechat', 'Api\WechatController@serve');

Route::namespace('Api')
    ->group(function () {
        Route::middleware(['auth:api'])
            ->group(function () {
                // dashboard
                Route::resource('current-user', 'UserController');
            });
    });

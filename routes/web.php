<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'api/v1', 'middleware' => ['wechat.oauth']],function(){
    Route::post('login','Api\UserController@login');
});


Route::group(['prefix' => 'api/v1', 'middleware' => ['api.auth']],function(){
    Route::post('register','Api\UserController@register');
});

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

//api-无需登陆
Route::group(['middleware'=>['OriginApi']],function(){

    Route::post('api/save_upload',['uses'=>'Api\DefaultController@saveUpload']);

    Route::get('api/qiniu_token','Api\DefaultController@getQiniuToken');//获取七牛云上传token


});

Route::group(['prefix' => 'api/v1', 'middleware' => ['wechat.oauth']],function(){
    Route::post('login','Api\UserController@login');
});


Route::group(['prefix' => 'api/v1', 'middleware' => ['api.auth']],function(){
    Route::post('register','Api\UserController@register');
});
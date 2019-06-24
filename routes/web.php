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
Route::post('local/upload','Controller@localUpload');//本地上传
Route::get('admin/login','Admin\AdminController@login');//后台登录页
Route::post('admin/login','Admin\AdminController@postLogin');//后台登录接口

Route::group(['prefix' => 'admin','middleware' => ['AdminAuth']],function(){
    Route::get('index','Admin\AdminController@index')->name('index');//首页
    Route::get('main','Admin\AdminController@main')->name('main');//默认页
    Route::get('sendMsg','Admin\AdminController@sendMsg')->name('main');//默认页
    Route::post('sendMsg','Admin\AdminController@postSendMsg')->name('main');//默认页
    Route::get('logout','Admin\AdminController@logout')->name('logout');//登出

    Route::get('admin_user',['uses'=>'Admin\AdminUserController@index','middleware'=>['SuperAuth']])->name('admin_user');//管理员页面
    Route::get('admin_user_list',['uses'=>'Admin\AdminUserController@lists','middleware'=>['SuperAuth']])->name('admin_user_list');//管理员页面
    Route::get('admin_add',['uses'=>'Admin\AdminUserController@add','middleware'=>['SuperAuth']])->name('admin_add');//管理员添加页面
    Route::post('admin_add',['uses'=>'Admin\AdminUserController@postAdd','middleware'=>['SuperAuth']])->name('admin_add');//管理员添加
    Route::post('admin_del',['uses'=>'Admin\AdminUserController@delete','middleware'=>['SuperAuth']])->name('admin_del');//管理员删除

    Route::post('user_edit',['uses'=>'Admin\UserController@postAdd','middleware'=>['SuperAuth']])->name('user_edit');//修改用户信息

    Route::get('admin_role',['uses'=>'Admin\AdminUserController@role','middleware'=>['SuperAuth']])->name('admin_role');//角色管理页面
    Route::get('admin_role_list',['uses'=>'Admin\AdminUserController@roleList','middleware'=>['SuperAuth']])->name('admin_role_list');//角色管理页面
    Route::get('admin_role_add',['uses'=>'Admin\AdminUserController@roleAdd','middleware'=>['SuperAuth']])->name('admin_role_add');//角色管理添加页面
    Route::post('admin_role_add',['uses'=>'Admin\AdminUserController@postRoleAdd','middleware'=>['SuperAuth']])->name('admin_role_add');//角色管理提交
    Route::post('admin_role_del',['uses'=>'Admin\AdminUserController@postRoleDel','middleware'=>['SuperAuth']])->name('admin_role_del');//角色管理删除


    Route::get('permission',['uses'=>'Admin\AdminUserController@permission','middleware'=>['SuperAuth']])->name('permission');//角色权限页面
    Route::post('permission',['uses'=>'Admin\AdminUserController@postPermission','middleware'=>['SuperAuth']])->name('permission');//角色权限修改


    Route::get('admin_operate',['uses'=>'Admin\AdminUserController@operate','middleware'=>['SuperAuth']])->name('admin_operate');//管理员操作日志
    Route::get('admin_operate_list',['uses'=>'Admin\AdminUserController@operateList','middleware'=>['SuperAuth']])->name('admin_operate');//管理员操作日志列表

    Route::get('user','Admin\UserController@index')->name('user');//用户页面
    Route::get('user_list','Admin\UserController@lists')->name('user_list');//用户列表
    Route::get('user_add','Admin\UserController@add')->name('user_add');//用户编辑页面
    Route::post('user_add','Admin\UserController@postAdd')->name('user_add');//用户编辑页面
    Route::get('user_log','Admin\AccountLogController@index')->name('user_log');//用户日志
    Route::get('log_list','Admin\AccountLogController@lists')->name('log_list');//用户日志列表
    Route::get('user_conf','Admin\UserController@conf')->name('user_conf');//调节用户
    Route::post('user_conf','Admin\UserController@postConf')->name('user_conf');//调节用户

    Route::get('user_real','Admin\UserRealController@index')->name('user_real');//实名页面
    Route::get('real_list','Admin\UserRealController@lists')->name('real_list');//实名列表
    Route::get('real_cat','Admin\UserRealController@cat')->name('real_cat');//查看实名信息
    Route::post('real_del','Admin\UserRealController@del')->name('real_del');//删除实名信息
    Route::post('real_check','Admin\UserRealController@check')->name('real_check');//通过实名信息


    Route::get('setting','Admin\SettingController@index')->name('setting');//基础设置
    Route::post('setting','Admin\SettingController@setting')->name('setting');//基础设置
    Route::get('count','Admin\CountController@index')->name('count');//商城统计
    Route::get('count/list','Admin\CountController@lists')->name('count');//商城统计


    Route::get('news_category','Admin\NewsController@newsCategoryIndex')->name('news_category');//新闻分类页面
    Route::get('news_category_list','Admin\NewsController@newsCategoryList')->name('news_category_list');//新闻分类列表
    Route::get('news_category_add','Admin\NewsController@newsCategoryAdd')->name('news_category_add');//添加新闻分类页面
    Route::post('news_category_add','Admin\NewsController@newsCategoryPostAdd')->name('news_category_add');//添加新闻分类
    Route::post('news_category_del','Admin\NewsController@newsCategoryPostDel')->name('news_category_del');//删除新闻分类


    Route::get('news','Admin\NewsController@index')->name('news');//新闻页面
    Route::get('news_list','Admin\NewsController@newsList')->name('news_list');//新闻列表
    Route::get('news_add','Admin\NewsController@newsAdd')->name('news_add');//添加新闻页面
    Route::post('news_add','Admin\NewsController@postNewsAdd')->name('news_add');//添加新闻
    Route::post('news_del','Admin\NewsController@postNewsDel')->name('news_del');//添加新闻

});


require __DIR__.'/custom_api.php';


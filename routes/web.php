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

Route::get('/', function () {
    return view('welcome');
});

//后台
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    //这里面写需要登录的路由
    Route::group(['middleware' => 'auth:admin'], function () {
        //系统模块
        Route::get('system/index/index', 'System\IndexController@index');
        Route::resource('system/user', 'System\UserController');
        Route::patch('system/user/{id}/enable', 'System\UserController@enable');
        Route::patch('system/user/{id}/disable',
                     'System\UserController@disable');
        Route::resource('system/role', 'System\RoleController');
        Route::patch('system/role/{id}/enable', 'System\RoleController@enable');
        Route::patch('system/role/{id}/disable',
                     'System\RoleController@disable');
        Route::resource('system/node', 'System\NodeController');
        Route::patch('system/node/{id}/enable', 'System\NodeController@enable');
        Route::patch('system/node/{id}/disable',
                     'System\NodeController@disable');
        Route::post('system/node/all/sort', 'System\NodeController@sort');
        Route::any('system/node/module/sort',
                   'System\NodeController@moduleSort');

        //其他模块

    });
    //这下面写不需要登录的路由
    Route::get('login', 'Auth\LoginController@showLoginForm');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

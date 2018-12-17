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

//Route::get('/', function () {
//    return view('welcome');
//});

/**后台**/
//重置路由跳回自己的首页
Route::get('/', 'Admin\Auth\LoginController@showLoginForm');
//正式定义后台路由
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    //重置路由跳回自己的首页
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::get('/index', 'Auth\LoginController@showLoginForm');
    //这里面写需要登录的路由
    Route::group(['middleware' => ['auth:admin', 'permission']], function () {
        //系统模块
        Route::get('system/index/index', 'System\IndexController@index');
        Route::any('system/index/config', 'System\IndexController@config');
        Route::any('system/index/updatePassword', 'System\IndexController@updatePassword');
        Route::any('system/index/setInfo', 'System\IndexController@setInfo');
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
        Route::post('system/node/sort', 'System\NodeController@sort');
        Route::any('system/node/module/sort',
                   'System\NodeController@moduleSort');
        Route::get('system/file', 'System\FileController@index');
        Route::any('system/file/config', 'System\FileController@config');
        Route::delete('system/file', 'System\FileController@destroy');

        //其他模块

    });
    //这下面写不需要登录的路由
    Route::get('login', 'Auth\LoginController@showLoginForm');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

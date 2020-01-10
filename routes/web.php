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

Route::any('/wx/link','WeiXin\WxController@index');
//字定义菜单
Route::get('/wx/create_menu','WeiXin\WxController@createMenu');
Route::get('/wx/access_token','WeiXin\WxController@AccessToken');         //刷新access_token
Route::get('/wx/sendMsg','WeiXin\WxController@sendMsg');       //微信群发

//后台登陆
Route::get('/admin/login','Admin\LoginController@login');
Route::post('/admin/do_login','Admin\LoginController@do_login');
//首页
Route::get('/admin/lists','Admin\IndexController@index');
//一周气温
Route::get('/admin/wechat','Admin\IndexController@wechat');
//素材管理
Route::get('/admin/media_add','Admin\MediaController@add');
Route::post('/admin/do_add','Admin\MediaController@do_add');
Route::get('/admin/media_show','Admin\MediaController@show');

//渠道管理
Route::get('/admin/channel_add','Admin\ChannelController@add');
Route::post('/admin/channel_do_add','Admin\ChannelController@do_add');
Route::get('/admin/channel_show','Admin\ChannelController@show');
Route::get('/admin/chart_show','Admin\ChannelController@chart_show');




//新闻
Route::get('xinwen/add','XinWen\XinWenController@add');
Route::post('xinwen/do_add','XinWen\XinWenController@do_add');
Route::get('xinwen/show','XinWen\XinWenController@show');
Route::get('xinwen/del/{id}','XinWen\XinWenController@del');
Route::get('xinwen/update/{id}','XinWen\XinWenController@update');
Route::post('xinwen/do_update/{id}','XinWen\XinWenController@do_update');
Route::get('xinwen/code','XinWen\XinWenController@code');       //带参数的二维码


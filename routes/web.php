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

/*Route::get('/', function () {
    return view('welcome');
});*/

//MasterController
Route::get('/','MasterController@welcome');//主页
Route::get('/welcome','MasterController@welcome');//主页
Route::get('/about','MasterController@about');//关于
Route::get('/office','MasterController@office');//办公区
Route::get('/forum','MasterController@forum');//讨论区
Route::get('/activity','MasterController@activity');//活动区
Route::get('/spaceAdministration','MasterController@spaceAdministration');//冷月航天局
Route::get('/factory','MasterController@factory');//冷月制造厂
Route::get('/archives','MasterController@archives');//冷月档案馆

//UserController
Route::get('/user/register','UserController@register');//注册页面
Route::post('/user/create','UserController@create');//注册页面后台
Route::get('/user/login','UserController@login');//登录页面
Route::post('/user/signIn','UserController@signIn');//登录页面后台
Route::get('/user/logout','UserController@logout');//退出登录

//OfficeController
Route::get('/office/create','OfficeController@create');//办公区新人报道创建页面
Route::post('/office/store','OfficeController@store');//办公区新人报道创建页面后台
Route::get('/office/show/{id}','OfficeController@show');//办公区新人报道显示页面
Route::get('/office/show/{id}/edit','OfficeController@edit');//办公区新人报道修改页面
Route::patch('/office/show/{id}/update','OfficeController@update');//办公区新人报道修改页面后台
Route::post('/office/show/welcome','OfficeController@welcome');//办公区新人报道显示页面迎新后台

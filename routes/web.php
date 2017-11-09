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
Route::get('/user/login',[ 'as' => 'login', 'uses' => 'UserController@login']);//登录页面
Route::post('/user/signIn','UserController@signIn');//登录页面后台
Route::get('/user/logout','UserController@logout');//退出登录
Route::get('/user/infoEdit','UserController@infoEdit');//个人信息修改页面
Route::post('/user/avatar','UserController@avatar');//上传头像
Route::post('/user/cropAvatar','UserController@cropAvatar');//裁剪上传头像

//OfficeController
Route::get('/office/create','OfficeController@create');//办公区新人报道创建页面
Route::post('/office/store','OfficeController@store');//办公区新人报道创建页面后台
Route::get('/office/show/{id}','OfficeController@show');//办公区新人报道显示页面
//Route::get('/office/show/{id}/edit','OfficeController@edit');//办公区新人报道修改页面
//Route::patch('/office/show/{id}/update','OfficeController@update');//办公区新人报道修改页面后台
Route::post('/office/show/welcome','OfficeController@welcome');//办公区新人报道显示页面迎新后台

//ForumController
Route::get('/forum/create','ForumController@create');//讨论区创建页面
Route::post('/forum/store','ForumController@store');//讨论区创建页面后台
Route::get('/forum/show/{id}','ForumController@show');//讨论区显示页面
Route::post('/forum/show/commit','ForumController@commit');//讨论区评论后台

//ActivityController
Route::get('/activity/publicChat','ActivityController@publicChat');//活动区公共聊天室
Route::post('/activity/showPublicChat','ActivityController@showPublicChat');//活动区公共聊天室后台
Route::get('/activity/publicChatLogout','ActivityController@publicChatLogout');//退出活动区公共聊天室

//FollowController
//Route::get('/follow/userDiscussionFollow/{discussion}','FollowController@userDiscussionFollow');//用户关注讨论

//VueHttpController
Route::get('/VueHttp/userDiscussionFollow/{discussion}','VueHttpController@userDiscussionFollow');//用户关注讨论
Route::get('/VueHttp/hasUserDiscussionFollow/{discussion}','VueHttpController@hasUserDiscussionFollow');//检查用户是否关注讨论
Route::get('/VueHttp/userUserFollow/{discussion}','VueHttpController@userUserFollow');//用户关注用户
Route::get('/VueHttp/hasUserUserFollow/{discussion}','VueHttpController@hasUserUserFollow');//检查用户是否关注用户

//NotificationController
Route::get('/notification/show','NotificationController@show');//用户关注用户消息通知

//AccountController
//Route::get('/account/officeStore/{id}','AccountController@officeStore');//新人报道获得活跃值
Route::get('/account/getLiveness/{id}','AccountController@getLiveness');//用户活跃值和等级
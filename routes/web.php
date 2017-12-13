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
Route::get('/user/userInfo/{id}','UserController@userInfo');//个人信息页面
Route::get('/user/doEmailConfirm','UserController@doEmailConfirm');//发送邮箱验证邮件
Route::get('/user/checkEmailConfirm/{email}/{emailConfirmCode}','UserController@checkEmailConfirm');//邮箱验证
Route::post('/user/avatar','UserController@avatar');//上传头像
Route::post('/user/cropAvatar','UserController@cropAvatar');//裁剪上传头像

//OfficeController
Route::get('/office/create','OfficeController@create');//办公区新人报道创建页面
Route::post('/office/store','OfficeController@store');//办公区新人报道创建页面后台
Route::get('/office/show/{id}','OfficeController@show');//办公区新人报道显示页面
Route::post('/office/show/welcome','OfficeController@welcome');//办公区新人报道显示页面迎新后台
//Route::get('/office/show/{id}/edit','OfficeController@edit');//办公区新人报道修改页面
//Route::patch('/office/show/{id}/update','OfficeController@update');//办公区新人报道修改页面后台

//ForumController
Route::get('/forum/create','ForumController@create');//讨论区创建页面
Route::post('/forum/store','ForumController@store');//讨论区创建页面后台
Route::get('/forum/show/{id}','ForumController@show');//讨论区显示页面
Route::post('/forum/show/commit','ForumController@commit');//讨论区评论后台

//ActivityController
Route::get('/activity/publicChat','ActivityController@publicChat');//活动区公共聊天室
Route::post('/activity/showPublicChat','ActivityController@showPublicChat');//活动区公共聊天室后台
Route::get('/activity/publicChatLogout','ActivityController@publicChatLogout');//退出活动区公共聊天室
Route::get('/activity/sign','ActivityController@sign');//活动区每日签到页面
Route::get('/activity/showSign','ActivityController@showSign');//活动区显示每日签到日历
Route::get('/activity/signIn/{nowDay}','ActivityController@signIn');//每日签到或者补签

//FollowController
Route::get('/follow/userDiscussionFollow/{discussion}','FollowController@userDiscussionFollow');//用户关注讨论
Route::get('/follow/hasUserDiscussionFollow/{discussion}','FollowController@hasUserDiscussionFollow');//检查用户是否关注讨论
Route::get('/follow/userUserFollow/{discussion}','FollowController@userUserFollow');//用户关注用户
Route::get('/follow/hasUserUserFollow/{discussion}','FollowController@hasUserUserFollow');//检查用户是否关注用户

//NotificationController
Route::get('/notification/showAll','NotificationController@showAll');//显示用户所有消息通知
Route::get('/notification/showUnread','NotificationController@showUnread');//显示用户未读消息通知

//AccountController
Route::get('/account/getLiveness/{id}','AccountController@getLiveness');//用户活跃值和等级

//SpaceAdministrationController
Route::get('/spaceAdministration/create','SpaceAdministrationController@create');//航天局发射新的卫星页面
Route::post('/spaceAdministration/upload','SpaceAdministrationController@upload');//航天局发射新的卫星上传图片后台
Route::post('/spaceAdministration/store','SpaceAdministrationController@store');//航天局发射新的卫星后台
Route::get('/spaceAdministration/show/{id}','SpaceAdministrationController@show');//航天局卫星显示页面
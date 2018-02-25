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

/*
 * 路由排序
 * MasterController
 * UserController
 * NotificationController
 * OfficeController
 * WarshipController
 * ForumController
 * FollowController
 * ActivityController
 * AccountController
 * SpaceAdministrationController
 */

/* MasterController */
Route::get('/','MasterController@welcome'); // 主页
Route::get('/welcome','MasterController@welcome'); // 主页
Route::get('/about','MasterController@about'); // 关于
Route::get('/office','MasterController@office'); // 办公区
Route::get('/office/warship','MasterController@warship'); // 办公区舰船信息管理中心页面
Route::get('/forum','MasterController@forum'); // 讨论区
Route::get('/activity','MasterController@activity'); // 活动区
Route::get('/spaceAdministration','MasterController@spaceAdministration'); // 冷月航天局
Route::get('/factory','MasterController@factory'); // 冷月制造厂
Route::get('/archives','MasterController@archives'); // 冷月档案馆

/* UserController */
Route::get('/user/register','UserController@register'); // 注册页面
Route::post('/user/create','UserController@create'); // 注册页面后台
Route::get('/user/login',[ 'as' => 'login', 'uses' => 'UserController@login']); // 登录页面
Route::post('/user/signIn','UserController@signIn'); // 登录页面后台
Route::get('/user/logout','UserController@logout'); // 退出登录
//Route::get('/user/sendEmailConfirm/{email}/{emailConfirmCode}','UserController@sendEmailConfirm'); // 发送邮箱验证邮件
Route::get('/user/reSendEmailConfirm/{email}','UserController@reSendEmailConfirm'); // 重新发送邮箱验证邮件
Route::get('/user/checkEmailConfirm/{email}/{emailConfirmCode}','UserController@checkEmailConfirm'); // 邮箱验证

Route::get('/user/center','UserController@center'); // 个人中心页面
Route::get('/user/center/info/{id}','UserController@info'); // 我的信息查看页面
Route::get('/user/center/infoEdit','UserController@infoEdit'); // 我的信息修改页面
Route::get('/user/center/avatarEdit','UserController@avatarEdit'); // 我的头像修改页面
Route::post('/user/center/avatar','UserController@avatar'); // 上传头像
Route::post('/user/center/avatarCrop','UserController@avatarCrop'); // 裁剪上传头像

/* NotificationController */
Route::get('/notification/showAll','NotificationController@showAll'); // 显示用户所有消息通知
Route::get('/notification/showUnread','NotificationController@showUnread'); // 显示用户未读消息通知

/* OfficeController 办公区 */
Route::get('/office/getIntroductions','OfficeController@getIntroductions'); // 获取新人报道列表
Route::get('/office/getIntroduction/{id}','OfficeController@getIntroduction'); // 获取新人报道
Route::get('/office/getMessages/{id}','OfficeController@getMessages'); // 获取新人报道回复列表

Route::get('/office/create','OfficeController@create'); // 新人报道创建页面
Route::post('/office/store','OfficeController@store'); // 新人报道创建页面后台
Route::get('/office/show/{id}','OfficeController@show'); // 新人报道显示页面
Route::post('/office/show/welcome','OfficeController@welcome'); // 新人报道显示页面迎新后台


/* WarshipController 办公区舰船管理 */
Route::get('/office/warship/create','WarshipController@create'); // 舰船管理创建页面
Route::post('/office/warship/store','WarshipController@store'); // 舰船管理创建页面后台
Route::post('/office/warship/picture','WarshipController@changePicture'); // 更改舰船立绘
Route::get('/office/warship/{id}/edit','WarshipController@edit'); // 舰船管理修改页面
Route::patch('/office/warship/{id}/update','WarshipController@update'); // 舰船管理修改页面后台
Route::get('/office/warship/getWarship','WarshipController@getWarship'); // 获得所有舰船数据
Route::get('/office/warship/{no}','WarshipController@gotoOne'); // 获得所有舰船数据

/* ForumController */
Route::get('/forum/getDiscussions','ForumController@getDiscussions'); // 获取讨论列表
Route::get('/forum/getDiscussion/{id}','ForumController@getDiscussion'); // 获取讨论
Route::get('/forum/getComments/{id}','ForumController@getComments'); // 获取评论列表

Route::get('/forum/create','ForumController@create'); // 讨论区创建页面
Route::post('/forum/store','ForumController@store'); // 讨论区创建页面后台
Route::get('/forum/show/{id}','ForumController@show'); // 讨论区显示页面
Route::post('/forum/show/comment','ForumController@comment'); // 讨论区评论后台

Route::get('/forum/softdelete/{id}','ForumController@softDelete'); //讨论软删除

/* FollowController */
Route::get('/follow/userDiscussionFollow/{discussion}','FollowController@userDiscussionFollow'); // 用户关注讨论
Route::get('/follow/hasUserDiscussionFollow/{discussion}','FollowController@hasUserDiscussionFollow'); // 检查用户是否关注讨论
Route::get('/follow/userUserFollow/{discussion}','FollowController@userUserFollow'); // 用户关注用户
Route::get('/follow/hasUserUserFollow/{discussion}','FollowController@hasUserUserFollow'); // 检查用户是否关注用户

/* ActivityController */
Route::get('/activity/publicChat','ActivityController@publicChat'); // 活动区公共聊天室
Route::post('/activity/showPublicChat','ActivityController@showPublicChat'); // 活动区公共聊天室后台
Route::get('/activity/publicChatLogout','ActivityController@publicChatLogout'); // 退出活动区公共聊天室
Route::get('/activity/sign','ActivityController@sign'); // 活动区每日签到页面
Route::get('/activity/showSign','ActivityController@showSign'); // 活动区显示每日签到日历
Route::get('/activity/signIn/{nowDay}','ActivityController@signIn'); // 每日签到或者补签

/* AccountController */
Route::get('/account/getLivenessAndLevel/{id}','AccountController@getLivenessAndLevel'); // 获得用户活跃值和等级

/* SpaceAdministrationController */
Route::get('/spaceAdministration/create','SpaceAdministrationController@create'); // 航天局发射新的卫星页面
Route::post('/spaceAdministration/upload','SpaceAdministrationController@upload'); // 航天局发射新的卫星上传图片后台
Route::post('/spaceAdministration/store','SpaceAdministrationController@store'); // 航天局发射新的卫星后台
Route::get('/spaceAdministration/show/{id}','SpaceAdministrationController@show'); // 航天局卫星显示页面
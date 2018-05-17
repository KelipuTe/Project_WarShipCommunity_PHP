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

Route::get('test',function(){
    event(new \App\Events\BroadcastNotification(1));
});

// ActivityController
Route::prefix('activity')->group(function() {
    Route::get('publicChat','ActivityController@publicChat'); // 活动区公共聊天室
    Route::post('showPublicChat','ActivityController@showPublicChat'); // 活动区公共聊天室后台
    Route::get('publicChatLogout','ActivityController@publicChatLogout'); // 退出活动区公共聊天室
    Route::get('sign','ActivityController@sign'); // 活动区每日签到页面
    Route::get('showSign','ActivityController@showSign'); // 活动区显示每日签到日历
    Route::get('signIn/{nowDay}','ActivityController@signIn'); // 每日签到或者补签
});

// AccountController
Route::prefix('account')->group(function() {
    Route::get('getLivenessAndLevel/{id}','AccountController@getLivenessAndLevel'); // 获得用户活跃值和等级
    Route::get('getTools','AccountController@getTools');
    Route::post('exchangeTool','AccountController@exchangeTool');
});

// BlacklistController
Route::prefix('office/blacklist')->group(function() {
    Route::get('/archives','BlacklistController@archives'); // 举报档案页面
    Route::get('notice','BlacklistController@notice'); // 举报处理公示页面
    Route::post('report','BlacklistController@report'); // 用户举报
    Route::get('getBlacklists','BlacklistController@getBlacklists'); // 获得举报列表
    Route::get('getDoneBlacklists','BlacklistController@getDoneBlacklists'); // 获得举报列表
    Route::post('locking','BlacklistController@locking'); // 获得举报列表
    Route::post('agree','BlacklistController@agree'); // 获得举报列表
});

// DiscussionController
Route::prefix('discussion')->group(function() {
    Route::get('getDiscussions','DiscussionController@getDiscussions'); // 获取讨论列表
    Route::get('getDiscussion/{id}','DiscussionController@getDiscussion'); // 获取讨论
    Route::get('getComments/{id}','DiscussionController@getComments'); // 获取评论列表

    Route::get('niceDiscussion/{id}','DiscussionController@niceDiscussion'); // 讨论推荐
    Route::get('niceComment/{id}','DiscussionController@niceComment'); // 回复点赞
    Route::get('getHotDiscussions','DiscussionController@getHotDiscussions'); // 获得热点讨论
    Route::get('getNiceDiscussions','DiscussionController@getNiceDiscussions'); // 获得推荐讨论

    Route::get('create','DiscussionController@create'); // 讨论区创建页面
    Route::post('store','DiscussionController@store'); // 讨论区创建页面后台
    Route::get('show/{id}','DiscussionController@show'); // 讨论区显示页面
    Route::post('show/comment','DiscussionController@comment'); // 讨论区评论后台

    Route::get('softdelete/{id}','DiscussionController@softdelete'); //讨论软删除
    Route::post('setTop','DiscussionController@setTop');
});

// FactoryController
Route::prefix('factory')->group(function() {
    Route::post('create','FactoryController@create'); // 创建模型
    Route::get('getFactories','FactoryController@getFactories'); // 获得所有模型
    Route::get('getFactory','FactoryController@getFactory'); // 获得模型
    Route::get('show/{id}','FactoryController@show'); // 显示模型
    Route::post('infoEdit','FactoryController@infoEdit'); // 修改模型信息
    Route::post('viewEdit','FactoryController@viewEdit'); // 修改模型视图
    Route::post('fileEdit','FactoryController@fileEdit'); // 修改模型文件
});

// FollowController
Route::prefix('follow')->group(function() {
    Route::get('userDiscussionFollow/{discussion}','FollowController@userDiscussionFollow'); // 用户关注讨论
    Route::get('hasUserDiscussionFollow/{discussion}','FollowController@hasUserDiscussionFollow'); // 检查用户是否关注讨论
    Route::get('userUserFollow/{discussion}','FollowController@userUserFollow'); // 用户关注用户
    Route::get('hasUserUserFollow/{discussion}','FollowController@hasUserUserFollow'); // 检查用户是否关注用户
});

// MasterController
Route::get('/','MasterController@discussion'); // 主页
Route::get('/welcome','MasterController@discussion'); // 主页
//Route::get('/','MasterController@welcome'); // 主页
//Route::get('/welcome','MasterController@welcome'); // 主页
Route::get('/about','MasterController@about'); // 关于
Route::get('error/{status}','MasterController@error');
Route::get('/office','MasterController@office'); // 办公区
Route::get('/office/warship','MasterController@warship'); // 办公区舰船信息管理中心页面
Route::get('/discussion','MasterController@discussion'); // 讨论区
Route::get('/activity','MasterController@activity'); // 活动区
Route::get('/spaceAdministration','MasterController@spaceAdministration'); // 冷月航天局
Route::get('/factory','MasterController@factory'); // 冷月制造厂
Route::get('/archives','MasterController@archives'); // 冷月档案馆

// NotificationController
Route::prefix('notification')->group(function() {
    Route::get('center','NotificationController@showAll'); // 显示用户所有消息通知
    Route::get('unread','NotificationController@showUnread'); // 显示用户未读消息通知
    Route::get('fromPersonalLetter','NotificationController@showFromPersonalLetter'); // 显示用户发送的私信
    Route::get('getFromPersonalLetter','NotificationController@getFromPersonalLetter'); // 获得用户发送的私信
    Route::get('toPersonalLetter','NotificationController@showToPersonalLetter'); // 显示向该用户发送的私信
    Route::get('getToPersonalLetter','NotificationController@getToPersonalLetter'); // 获得向该用户发送的私信
    Route::post('messageStore','NotificationController@messageStore'); // 保存用户私信并发出站内信通知
});

// OfficeController 办公区
Route::prefix('office')->group(function() {
    Route::get('getIntroductions','OfficeController@getIntroductions'); // 获取新人报道列表
    Route::get('getIntroduction/{id}','OfficeController@getIntroduction'); // 获取新人报道
    Route::get('getMessages/{id}','OfficeController@getMessages'); // 获取新人报道回复列表

    Route::get('create','OfficeController@create'); // 新人报道创建页面
    Route::post('store','OfficeController@store'); // 新人报道创建页面后台
    Route::get('show/{id}','OfficeController@show'); // 新人报道显示页面
    Route::post('show/welcome','OfficeController@welcome'); // 新人报道显示页面迎新后台
});

// PermissionController
Route::prefix('office/permission')->group(function (){
    Route::get('/','PermissionController@index');
    Route::post('addRole','PermissionController@addRole');
    Route::get('getRoles','PermissionController@getRoles');
    Route::get('role/{id}','PermissionController@role');
    Route::get('getRole','PermissionController@getRole');
    Route::post('addPermission','PermissionController@addPermission');
    Route::post('togglePermission','PermissionController@togglePermission');
    Route::post('giveRoleToUser','PermissionController@giveRoleToUser');
    Route::post('removeRoleFromUser','PermissionController@removeRoleFromUser');
});

// SpaceAdministrationController
Route::prefix('spaceAdministration')->group(function() {
    Route::get('create','SpaceAdministrationController@create'); // 航天局发射新的卫星页面
    Route::post('upload','SpaceAdministrationController@upload'); // 航天局发射新的卫星上传图片后台
    Route::post('store','SpaceAdministrationController@store'); // 航天局发射新的卫星后台
    Route::get('show/{id}','SpaceAdministrationController@show'); // 航天局卫星显示页面
    Route::get('getSatellites','SpaceAdministrationController@getSatellites'); //获得所有的在轨卫星
});

// TagController
Route::prefix('tag')->group(function() {
    Route::get('getTags/{type}/{id}','TagController@getTags'); // 获取指定对象的标签
    Route::get('getAllTags','TagController@getAllTags'); // 获取所有标签
    Route::post('createTag','TagController@createTag'); // 新建标签
    Route::post('changeTag','TagController@changeTag'); // 改变标签
});

// UserController
Route::prefix('user')->group(function(){
    Route::get('register','UserController@register'); // 注册页面
    Route::post('create','UserController@create'); // 注册页面后台
    Route::get('login',[ 'as' => 'login', 'uses' => 'UserController@login']); // 登录页面
    Route::post('signIn','UserController@signIn'); // 登录页面后台
    Route::get('logout','UserController@logout'); // 退出登录
//    Route::get('sendEmailConfirm/{email}/{emailConfirmCode}','UserController@sendEmailConfirm'); // 发送邮箱验证邮件
    Route::get('reSendEmailConfirm/{email}','UserController@reSendEmailConfirm'); // 重新发送邮箱验证邮件
    Route::get('checkEmailConfirm/{email}/{emailConfirmCode}','UserController@checkEmailConfirm'); // 邮箱验证
});
Route::prefix('user/center')->group(function() {
    Route::get('/', 'UserController@center'); // 个人中心页面
    Route::get('info/{id}', 'UserController@info'); // 我的信息查看页面
    Route::get('infoEdit', 'UserController@infoEdit'); // 我的信息修改页面
    Route::get('avatarEdit', 'UserController@avatarEdit'); // 我的头像修改页面
    Route::post('avatar', 'UserController@avatar'); // 上传头像
    Route::post('avatarCrop', 'UserController@avatarCrop'); // 裁剪上传头像
    Route::get('account', 'UserController@account'); //
});

// WarshipController
Route::prefix('office/warship')->group(function() {
    Route::get('create','WarshipController@create'); // 舰船管理创建页面
    Route::post('store','WarshipController@store'); // 舰船管理创建页面后台
    Route::post('picture','WarshipController@changePicture'); // 更改舰船立绘
    Route::get('{id}/edit','WarshipController@edit'); // 舰船管理修改页面
    Route::patch('{id}/update','WarshipController@update'); // 舰船管理修改页面后台
    Route::get('getWarship','WarshipController@getWarship'); // 获得所有舰船数据
    Route::get('{name}','WarshipController@gotoOne'); // 获得所有舰船数据
});
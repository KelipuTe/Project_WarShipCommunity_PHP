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

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('test',function(){

});

// ActivityController
Route::prefix('activity')->group(function() {
    Route::get('/','ActivityController@activity'); // 活动区
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
    Route::get('getTools','AccountController@getTools'); // 获得用户道具信息
    Route::post('exchangeTool','AccountController@exchangeTool'); // 兑换道具
});

// BlacklistController 黑名单控制器
Route::prefix('office/blacklist')->group(function() {
    Route::post('report','BlacklistController@report'); // 用户举报
    Route::get('archives','BlacklistController@archives'); // 举报信息页面
    Route::get('getBlacklists','BlacklistController@getBlacklists'); // 获得举报列表
    Route::get('locking','BlacklistController@locking'); // 获得举报对象
    Route::post('handel','BlacklistController@handel'); // 处理举报信息
    Route::get('notice','BlacklistController@notice'); // 举报处理公示页面
    Route::get('getDoneBlacklists','BlacklistController@getDoneBlacklists'); // 获得处理过的举报列表
});

// DiscussionController 讨论区控制器
Route::prefix('discussion')->group(function() {
    Route::get('/','DiscussionController@discussion'); // 讨论区
    Route::get('discussionCreate','DiscussionController@discussionCreate'); // 讨论创建页面
    Route::post('discussionStore','DiscussionController@discussionStore'); // 保存讨论
    Route::get('show/{id}','DiscussionController@show'); // 讨论显示页面
    Route::get('getDiscussion/{id}','DiscussionController@getDiscussion'); // 获取讨论
    Route::post('commentStore','DiscussionController@commentStore'); // 保存回复
    Route::post('niceDiscussion','DiscussionController@niceDiscussion'); // 推荐讨论
    Route::post('niceComment','DiscussionController@niceComment'); // 回复点赞
    Route::post('softdelete','DiscussionController@softdelete'); // 讨论软删除
    Route::post('setTop','DiscussionController@setTop'); // 讨论置顶
});

// FactoryController
Route::prefix('factory')->group(function() {
    Route::get('/','FactoryController@factory'); // 冷月制造厂
    Route::post('create','FactoryController@create'); // 创建模型
    Route::get('getFactories','FactoryController@getFactories'); // 获得所有模型
    Route::get('show/{id}','FactoryController@show'); // 显示模型
    Route::get('getFactory','FactoryController@getFactory'); // 获得模型
    Route::post('infoEdit','FactoryController@infoEdit'); // 修改模型信息
    Route::post('viewEdit','FactoryController@viewEdit'); // 修改模型视图
    Route::post('fileEdit','FactoryController@fileEdit'); // 修改模型文件
});

// FollowController 关注控制器
Route::prefix('follow')->group(function() {
    Route::get('userDiscussionFollow/{discussion}','FollowController@userDiscussionFollow'); // 用户关注讨论
    Route::get('hasUserDiscussionFollow/{discussion}','FollowController@hasUserDiscussionFollow'); // 检查用户是否关注讨论
    Route::get('userUserFollow/{discussion}','FollowController@userUserFollow'); // 用户关注用户
    Route::get('hasUserUserFollow/{discussion}','FollowController@hasUserUserFollow'); // 检查用户是否关注用户
});

// MasterController
Route::prefix('master')->group(function() {
    Route::get('getUser','MasterController@getUser'); // 获得用户信息
});
Route::get('/','DiscussionController@discussion'); // 主页面
Route::get('/about','MasterController@about'); // 关于页面
Route::get('error/{status}','MasterController@error'); // 错误页面
Route::get('/archives','MasterController@archives'); // 冷月档案馆

// NotificationController 消息通知控制器
Route::prefix('notification')->group(function() {
    Route::get('center','NotificationController@showAll'); // 显示用户所有消息通知
    Route::get('unread','NotificationController@showUnread'); // 显示用户未读消息通知

    Route::post('personalLetterStore','NotificationController@personalLetterStore'); // 保存私信，并发出消息通知
    Route::get('getPersonalLetters','NotificationController@getPersonalLetters'); // 获得两个用户之间的私信，并标记消息已读
    Route::get('personalLetter','NotificationController@personalLetter'); // 消息中心，私信消息页面
    Route::get('getContacts','NotificationController@getContacts'); // 查找与本用户有过交互的用户
});

// OfficeController 办公区控制器
Route::prefix('office')->group(function() {
    Route::get('/','OfficeController@office'); // 办公区
    Route::get('create','OfficeController@create'); // 报道创建页面
    Route::post('introductionStore','OfficeController@introductionStore'); // 报道创建页面后台
    Route::get('getIntroductions','OfficeController@getIntroductions'); // 获取报道列表
    Route::get('show/{id}','OfficeController@show'); // 报道显示页面
    Route::get('getIntroduction/{id}','OfficeController@getIntroduction'); // 获取报道
    Route::post('messageStore','OfficeController@messageStore'); // 报道回复
    Route::get('getMessages/{id}','OfficeController@getMessages'); // 获取报道回复列表

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
    Route::get('/','SpaceAdministrationController@spaceAdministration'); // 冷月航天局
    Route::get('create','SpaceAdministrationController@create'); // 航天局发射新的卫星页面
    Route::post('upload','SpaceAdministrationController@upload'); // 航天局发射新的卫星上传图片后台
    Route::post('store','SpaceAdministrationController@store'); // 航天局发射新的卫星后台
    Route::get('show/{id}','SpaceAdministrationController@show'); // 航天局卫星显示页面
    Route::get('getSatellites','SpaceAdministrationController@getSatellites'); //获得所有的在轨卫星
});

// TagController 标签控制器
Route::prefix('tag')->group(function() {
    Route::get('getTags','TagController@getTags'); // 获取指定对象的标签
    Route::get('getAllTags','TagController@getAllTags'); // 获取所有标签
    Route::post('createTag','TagController@createTag'); // 新建标签
    Route::post('changeTag','TagController@changeTag'); // 改变标签
});

// UserController
Route::prefix('user')->group(function(){
    Route::get('register',[ 'as' => 'register', 'uses' => 'UserController@register']); // 注册页面
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

// WarshipController 舰船信息管理控制器
Route::prefix('office/warship')->group(function() {
    Route::get('/','WarshipController@warship'); // 舰船管理中心页面
    Route::get('create','WarshipController@create'); // 舰船创建页面
    Route::post('store','WarshipController@store'); // 舰船创建
    Route::post('picture','WarshipController@changePicture'); // 更改舰船立绘
    Route::get('getWarship','WarshipController@getWarship'); // 获得所有舰船数据
    Route::get('{id}/edit','WarshipController@edit'); // 舰船修改页面
    Route::patch('{id}/update','WarshipController@update'); // 舰船修改

});
<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// DiscussionApiController 讨论区 api 控制器
Route::prefix('discussion')->group(function () {
    Route::get('getDiscussions','DiscussionApiController@apiGetDiscussions'); // 获得讨论列表
    //Route::get('discussion/{id}','DiscussionApiController@apiGetDiscussion');
    Route::get('getHotDiscussions','DiscussionApiController@apiGetHotDiscussions'); // 获得热点讨论
    Route::get('getNiceDiscussions','DiscussionApiController@apiGetNiceDiscussions'); // 获得推荐讨论
    Route::get('getComments/{id}','DiscussionApiController@apiGetComments'); // 获取回复列表
});

// UserApiController 用户模块 api 控制器
Route::prefix('user/center')->group(function () {
    Route::get('getUserInfo','UserApiController@apiGetUserInfo')->middleware('auth:api'); // 获得用户信息
});
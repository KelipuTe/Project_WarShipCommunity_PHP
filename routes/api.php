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

Route::prefix('discussion')->group(function () {
    Route::get('getDiscussions','DiscussionApiController@apiGetDiscussions'); // 获得讨论列表
//    Route::get('discussion/{id}','DiscussionApiController@apiGetDiscussion');
    Route::get('getHotDiscussions','DiscussionApiController@apiGetHotDiscussions'); // 获得热点讨论
    Route::get('getNiceDiscussions','DiscussionApiController@apiGetNiceDiscussions'); // 获得推荐讨论
});

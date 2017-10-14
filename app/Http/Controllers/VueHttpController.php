<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

/**
 * 这个控制器负责处理Vue.js组件的Http请求
 * Class VueHttpController
 * @package App\Http\Controllers
 */
class VueHttpController extends Controller
{
    /**
     * user关注discussion
     * @param $discussion
     * @return mixed
     */
    public function userDiscussionFollow($discussion){
        Auth::user()->userDiscussionFollow($discussion);
        return \Response::json([
            'userDiscussion' => Auth::user()->hasFollowedDiscussion($discussion)
        ]);
    }

    /**
     * 检查user是否关注discussion
     * @param $discussion
     * @return mixed
     */
    public function hasUserDiscussionFollow($discussion){
        return \Response::json([
            'userDiscussion' => Auth::user()->hasFollowedDiscussion($discussion)
        ]);
    }
}

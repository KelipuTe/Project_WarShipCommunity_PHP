<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\User;
use Auth;
use Illuminate\Http\Request;

/**
 * 这个控制器负责处理Vue.js组件的Http请求
 * Class VueHttpController
 * @package App\Http\Controllers
 */
class VueHttpController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * user关注discussion
     * @param $discussion
     * @return mixed
     */
    public function userDiscussionFollow($discussion){
        Auth::user()->userDiscussionFollow($discussion);//获取自己的user对象调用关注用户讨论
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

    /**
     * user关注user
     * @param $discussion_id
     * @return mixed
     */
    public function userUserFollow($discussion_id){
        $discussion = Discussion::find($discussion_id);//通过discussion_id找到discussion对象
        $user = User::find($discussion->user->id);//通过discussion对象找到关注的user对象
        Auth::user()->userUserFollow($user);//获取自己的user对象调用关注用户函数
        return $this->hasUserUserFollow($discussion_id);
    }

    /**
     * 检查user是否关注user
     * @param $discussion_id
     * @return mixed
     */
    public function hasUserUserFollow($discussion_id){
        $discussion = Discussion::find($discussion_id);//通过discussion_id找到discussion对象
        $user = User::find($discussion->user->id);//通过discussion对象找到user对象
        $followers = $user->userUserFollower()->pluck('follower_id')->toArray();//通过user对象找到所有的follower
        return \Response::json([
            'userUser' => in_array(Auth::user()->id,$followers)
        ]);
    }
}

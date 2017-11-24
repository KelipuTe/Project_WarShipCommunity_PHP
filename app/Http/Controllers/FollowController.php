<?php

namespace App\Http\Controllers;

use Auth;
use App\Discussion;
use App\Notifications\UserUserFollowNotification;
use App\User;
use Illuminate\Http\Request;

/**
 * 这个控制器负责各种关注的方法
 * Class FollowController
 * @package App\Http\Controllers
 */
class FollowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['only' => ['userDiscussionFollow']]);
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
        $discussion = Discussion::find($discussion_id);
        $user = User::find($discussion->user->id);
        $followers = $user->userUserFollower()->pluck('follower_id')->toArray();
        if(!in_array(Auth::user()->id,$followers)){
            /*如果还没有关注目标用户*/
            Auth::user()->userUserFollow($user);//获取自己的user对象调用关注用户函数
            $user->notify(new UserUserFollowNotification());//关注用户完成后发送站内信通知被关注用户
        } else {
            /*否则取消关注目标用户*/
            Auth::user()->userUserFollow($user);
        }
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

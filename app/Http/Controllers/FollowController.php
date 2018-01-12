<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use Illuminate\Http\Request;

use App\User;
use App\Discussion;
use App\Notifications\UserUserFollowNotification;

/**
 * 这个控制器负责各种关注事件的处理
 * Class FollowController
 * @package App\Http\Controllers
 */
class FollowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * user 关注 discussion
     * @param $discussion
     * @return mixed
     */
    public function userDiscussionFollow($discussion){
        Auth::user()->userDiscussionFollow($discussion); // 获取自己的 user 对象调用关注用户讨论函数
        return Response::json([
            'userDiscussion' => Auth::user()->hasFollowedDiscussion($discussion)
        ]);
    }

    /**
     * 检查 user 是否关注 discussion
     * @param $discussion
     * @return mixed
     */
    public function hasUserDiscussionFollow($discussion){
        return Response::json([
            'userDiscussion' => Auth::user()->hasFollowedDiscussion($discussion)
        ]);
    }

    /**
     * user 关注 user
     * @param $discussion_id
     * @return mixed
     */
    public function userUserFollow($discussion_id){
        $discussion = Discussion::find($discussion_id);
        $user = User::find($discussion->user->id);
        $followers = $user->userUserFollower()->pluck('follower_id')->toArray();
        if(!in_array(Auth::user()->id,$followers)){
            /* 如果还没有关注目标用户 */
            Auth::user()->userUserFollow($user); // 获取自己的 user 对象调用关注用户函数
            $user->notify(new UserUserFollowNotification()); // 关注用户完成后发送站内信通知被关注用户
        } else {
            /* 否则取消关注目标用户 */
            Auth::user()->userUserFollow($user);
        }
        return $this->hasUserUserFollow($discussion_id);
    }

    /**
     * 检查 user 是否关注 user
     * @param $discussion_id
     * @return mixed
     */
    public function hasUserUserFollow($discussion_id){
        $discussion = Discussion::find($discussion_id); // 通过 discussion_id 找到 discussion 对象
        $user = User::find($discussion->user->id); // 通过 discussion 对象找到 user 对象
        $followers = $user->userUserFollower()->pluck('follower_id')->toArray(); // 通过 user 对象找到所有的 follower
        return Response::json([
            'userUser' => in_array(Auth::user()->id,$followers)
        ]);
    }
}

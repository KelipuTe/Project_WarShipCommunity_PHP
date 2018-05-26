<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use Illuminate\Http\Request;

use App\User;
use App\Discussion;

/**
 * Class FollowController [关注控制器]
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
     * @return mixed
     */
    public function userDiscussionFollow(){
        Auth::user()->userDiscussionFollow(request('id')); // 获取自己的 user 对象调用关注用户讨论函数
        return $this->hasUserDiscussionFollow(request('id'));
    }

    /**
     * 检查 user 是否关注 discussion
     * @param int $id [discussion_id]
     * @return mixed
     */
    public function hasUserDiscussionFollow($id = 0){
        if($id == 0){
            $id = request('id');
        }
        $discussion = Discussion::find($id);
        $isFollowed = Auth::user()->hasFollowedDiscussion($id);
        return Response::json([
            'countFollowedUser' => $discussion->countFollowedUser(),
            'isFollowed' => $isFollowed
        ]);
    }

    /**
     * user 关注 user
     * @return mixed
     */
    public function userUserFollow(){
        $discussion = Discussion::find(request('id'));
        $user = User::find($discussion->user->id);
        $followers = $user->userUserFollower()->pluck('follower_id')->toArray();
        if(!in_array(Auth::user()->id,$followers)){
            // 如果还没有关注目标用户
            Auth::user()->userUserFollow($user); // 获取自己的 user 对象调用关注用户函数
        } else {
            // 否则取消关注目标用户
            Auth::user()->userUserFollow($user);
        }
        return $this->hasUserUserFollow(request('id'));
    }

    /**
     * 检查 user 是否关注 user
     * @param $id [discussion_id]
     * @return mixed
     */
    public function hasUserUserFollow($id = 0){
        if($id == 0){
            $id = request('id');
        }
        $discussion = Discussion::find($id); // 通过 discussion_id 找到 discussion 对象
        $user = User::find($discussion->user->id); // 通过 discussion 对象找到 user 对象
        $followers = $user->userUserFollower()->pluck('follower_id')->toArray(); // 通过 user 对象找到所有的 follower
        $isFollowed = in_array(Auth::user()->id,$followers);
        return Response::json([
            'userAvatar' => $discussion->user->avatar,
            'username' => $discussion->user->username,
            'user_id' => $discussion->user_id,
            'countUserDiscussions' => $discussion->user->discussions->count(),
            'countUserComments' => $discussion->user->comments->count(),
            'countUserFollowers' => $discussion->user->userUserFollower->count(),
            'isFollowed' => $isFollowed,
        ]);
    }
}

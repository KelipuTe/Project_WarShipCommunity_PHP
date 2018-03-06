<?php

namespace App\Http\Controllers;

use App\Notifications\PersonalLetterNotification;
use App\PersonalLetter;
use App\User;
use Auth;
use Response;
use Illuminate\Http\Request;

/**
 * 消息通知模块控制器
 * Class NotificationController
 * @package App\Http\Controllers
 */
class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 显示用户所有消息通知
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAll(){
        $user = Auth::user();
        $notifications = $user->notifications;  // 查找 user 所有的消息通知
        return view('/user/notification/center',compact('notifications'));
    }

    /**
     * 显示用户未读消息通知
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUnread(){
        $user = Auth::user();
        $notifications = $user->unreadNotifications; // 查找 user 所有未读的消息通知
        $user->unreadNotifications->markAsRead(); // 将所有未读的消息通知设为已读
        return view('/user/notification/unread',compact('notifications'));
    }

    /**
     * 显示用户发送的私信
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFromPersonalLetter(){
        return view('/user/notification/fromPersonalLetter');
    }

    /**
     * 获得用户发送的私信
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFromPersonalLetter(){
        $user = Auth::user();
        $personalLetters = $user->fromPersonalLetter; // 查找该用户发送的私信
        return Response::json([
            'personalLetters' => $personalLetters,
        ]);
    }

    /**
     * 显示向该用户发送的私信
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showToPersonalLetter(){
        return view('/user/notification/toPersonalLetter');
    }

    /**
     * 获得向该用户发送的私信
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getToPersonalLetter(){
        $user = Auth::user();
        $personalLetters = $user->toPersonalLetter; // 查找向该用户发送的私信
        return Response::json([
            'personalLetters' => $personalLetters,
        ]);
    }

    /**
     * 保存用户私信并发出站内信通知
     * @return mixed
     */
    public function messageStore(){
        $personalLetter = PersonalLetter::create([
            'body' => request('body'),
            'to_user_id' => request('to_user_id'),
            'from_user_id' => Auth::user()->id
        ]);
        $to_user = User::findOrFail(request('to_user_id'));
        $to_user->notify(new PersonalLetterNotification($personalLetter->id));
        return Response::json([
            'message' => '成功',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

/**
 * 这个控制器负责管理消息通知
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
        return view('/notifications/show',compact('notifications'));
    }

    /**
     * 显示用户未读消息通知
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUnread(){
        $user = Auth::user();
        $notifications = $user->unreadNotifications; // 查找 user 所有未读的消息通知
        $user->unreadNotifications->markAsRead(); // 将所有未读的消息通知设为已读
        return view('/notifications/show',compact('notifications'));
    }
}

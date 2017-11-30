<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

/**
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
        $notifications = $user->notifications;
        return view('/notifications/show',compact('notifications'));
    }

    /**
     * 显示用户未读消息通知
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUnread(){
        $user = Auth::user();
        $notifications = $user->unreadNotifications;
        $user->unreadNotifications->markAsRead();
        return view('/notifications/show',compact('notifications'));
    }
}

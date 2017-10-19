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
    /**
     * 用户关注用户消息通知
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userUserNotification(){
        $user = Auth::user();
        return view('/notifications/userUserFollow',compact('user'));
    }
}

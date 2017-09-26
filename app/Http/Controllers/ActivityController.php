<?php

namespace App\Http\Controllers;

use App\ChatMessage;
use App\Events\PublicChat;
use App\Events\PublicChatUserSignIn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

/**
 * 这个控制器负责活动区
 * Class ActivityController
 * @package App\Http\Controllers
 */
class ActivityController extends Controller
{
    /**
     * 公共聊天室页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function publicChat(){
        $key = "publicChatSignIn";
        if(\Auth::check()){
            //PublicChatUserSignIn，type=1表示有用户进入公共聊天室
            event(new PublicChatUserSignIn(1,\Auth::user()->username));
        }
        $userList = Redis::smembers($key);
        return view('activity/publicChat',compact('userList'));
    }

    /**
     * 退出公共聊天室
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function publicChatLogout(){
        if(\Auth::check()){
            //PublicChatUserSignIn，type=-1表示有用户退出公共聊天室
            event(new PublicChatUserSignIn(-1,\Auth::user()->username));
        }
        return redirect('/welcome');
    }

    /**
     * 公共聊天室后台
     * @param Request $request
     */
    public function showPublicChat(Request $request){
        $chatMessage = new ChatMessage();
        $chatMessage->setUsername(\Auth::user()->username);
        $chatMessage->setChatMessage($request->get('body'));
        $chatMessage->setTime(Carbon::now());
        event(new PublicChat($chatMessage));
    }

}

<?php

namespace App\Http\Controllers;

use App\ChatMessage;
use App\Events\PublicChat;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        return view('activity/publicChat');
    }

    public function showPublicChat(Request $request){
        $chatMessage = new ChatMessage();
        $chatMessage->setUsername(\Auth::user()->username);
        $chatMessage->setChatMessage($request->get('body'));
        $chatMessage->setTime(Carbon::now());
        event(new PublicChat($chatMessage));
    }
}

<?php

namespace App\Http\Controllers;

use App\Sign;
use Auth;
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
        if(Auth::check()){
            /*PublicChatUserSignIn，type=1表示有用户进入公共聊天室*/
            event(new PublicChatUserSignIn(1,Auth::user()->username));
        }
        $userList = Redis::smembers($key);//从 Redis Set 集合里取数据
        return view('activity/publicChat',compact('userList'));
    }

    /**
     * 退出公共聊天室
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function publicChatLogout(){
        if(Auth::check()){
            /*PublicChatUserSignIn，type=-1表示有用户退出公共聊天室*/
            event(new PublicChatUserSignIn(-1,Auth::user()->username));
        }
        return redirect('/welcome');
    }

    /**
     * 公共聊天室后台
     * @param Request $request
     */
    public function showPublicChat(Request $request){
        $chatMessage = new ChatMessage();
        $chatMessage->setUsername(Auth::user()->username);
        $chatMessage->setChatMessage($request->get('body'));
        $chatMessage->setTime(Carbon::now());
        event(new PublicChat($chatMessage));
    }

    /**
     * 每日签到页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sign(){
        return view('activity/sign');
    }

    /**
     * 显示每日签到日历
     * @return mixed
     */
    public function showSign(){
        $user = Auth::user();
        $nowTime = Carbon::now();
        $sign = Sign::all()
            ->where('user_id',Auth::user()->id)
            ->where('year','=',$nowTime->year)
            ->where('month','=',$nowTime->month)
            ->first();
        if($sign == null){
            /*如果没有当前月份的签到信息就用当前月份的信息创建一个新的表*/
            $dayNum = $nowTime->daysInMonth;
            $day = "0";
            for($i = 1;$i < $dayNum;++$i){
                $day = $day.',0';
            }
            $data = [
                'user_id' => Auth::user()->id,
                'year' => $nowTime->year,
                'month' => $nowTime->month,
                'day' => $day
            ];
            $sign = Sign::create(array_merge($data));
        }
        return \Response::json([
            'year' => $sign->year,
            'month' => $sign->month,
            'day' => $sign->day,
            'combo' => $sign->combo
        ]);
    }

    /**
     * 每日签到或者补签
     * @return mixed
     */
    public function signIn($nowDay){
        $user = Auth::user();
        $nowTime = Carbon::now();
        $sign = Sign::all()
            ->where('user_id',Auth::user()->id)
            ->where('year','=',$nowTime->year)
            ->where('month','=',$nowTime->month)
            ->first();
        if($sign == null){
            /*如果没有当前月份的签到信息就用当前月份的信息创建一个新的表*/
            $dayNum = $nowTime->daysInMonth;
            $day = "0";
            for($i = 1;$i < $dayNum;++$i){
                $day = $day.',0';
            }
            $data = [
                'user_id' => Auth::user()->id,
                'year' => $nowTime->year,
                'month' => $nowTime->month,
                'day' => $day
            ];
            $sign = Sign::create(array_merge($data));
        }
        $dayArray = explode(',',$sign->day);
        $dayArray[$nowDay-1] = '1';
        $daysign = $dayArray[0];
        for($i=1;$i<count($dayArray);++$i){
            $daysign = $daysign.','.$dayArray[$i];
        }
        $sign->update(['day'=>$daysign]);
        return $this->showSign();
    }

}

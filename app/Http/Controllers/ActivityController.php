<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\ChatMessage;
use App\Events\PublicChat;
use App\Events\PublicChatUserSignIn;
use App\Sign;

/**
 * 这个控制器负责活动区
 * Class ActivityController
 * @package App\Http\Controllers
 */
class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        $userLists = Redis::smembers($key);//从 Redis Set 集合里取数据
        return view('activity/publicChat',compact('userLists'));
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
        return Response::json([
            'year' => $sign->year,
            'month' => $sign->month,
            'day' => $sign->day,
            'total' => $this->totalCount(),
            'combo' => $this->comboCount($sign->day,$nowTime->day)
        ]);
    }

    /**
     * 每日签到或者补签
     * @param $nowDay
     * @return mixed
     */
    public function signIn($nowDay){
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
        $dayArray = explode(',',$sign->day);//把字符串拆分成数组
        $dayArray[$nowDay-1] = '1';//今天签到
        $daysign = $dayArray[0];//把拆分的数组组装成原来的字符串
        for($i=1;$i<count($dayArray);++$i){
            $daysign = $daysign.','.$dayArray[$i];
        }
        $sign->update(['day'=>$daysign]);//更新数据库
        $this->livenessCount($sign->day,$nowTime->day);//签到获得活跃值
        return $this->showSign();
    }

    /**
     * 计算用户累计签到天数
     * @return int
     */
    function totalCount(){
        $signs = Sign::all()->where('user_id',Auth::user()->id);//取出用户所有的签到表
        if($signs == null){
            return 0;
        }
        $count = 0;
        foreach ($signs as $sign){
            $count = $count + substr_count($sign->day,'1');//统计目标字符串内某字符串出现的次数
        }
        return $count;
    }

    /**
     * 计算当月连续签到天数
     * @param $day
     * @param $i_day
     * @return int
     */
    function comboCount($day,$i_day){
        $combo = 0;
        $days = explode(',',$day);
        /*这里先判断下表是否越界，如果从当月第一天就连续签到的话，先判断是否签到会造成下标越界错误*/
        while($i_day > 0 && $days[$i_day-1] != '0'){
            $combo = $combo + 1;
            --$i_day;
        }
        return $combo;
    }

    /**
     * 签到获得活跃值
     * @param $day
     * @param $i_day
     */
    function livenessCount($day,$i_day){
        $total = $this->totalCount();
        $combo = $this->comboCount($day,$i_day);
        $power = 1 + (int)($total/10) + (int)($combo/7);//签到活跃值倍率=基础倍率1+用户累计签到天数/10+当月连续签到天数/7
        $accountController = new AccountController();
        $accountController->activitySign(Auth::user()->id,$power);//增加活跃值
    }

}

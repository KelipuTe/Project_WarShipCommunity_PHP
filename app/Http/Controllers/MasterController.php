<?php

namespace App\Http\Controllers;

use App\Satellite;
use App\Warship;
use Auth;
use Response;
use Illuminate\Support\Facades\Redis;

use App\Discussion;
use App\Introduction;

/**
 * Class MasterController [主控制器]
 * @package App\Http\Controllers
 */
class MasterController extends Controller
{
    /**
     * 首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function welcome(){
        /*
         * Redis 数据库使用案例
         * Redis::set('kelipute','kelipute');
         * $name = Redis::get('kelipute');
         * dd($name);
         */
        return view('master/welcome');
    }

    /**
     * 关于
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about(){
        return view('master/about');
    }

    /**
     * 档案馆
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function archives(){
        return view('archives/archives');
    }

    /**
     * 错误页面
     * @param $status
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function error($status){
        switch($status){
            case '401':
                $message = "没有足够的权限"; break;
            case '404':
                $message = "页面不存在"; break;
            default:
                $message = "页面不存在";
        }
        return view('master.error',compact('message',$message));
    }

    /**
     * 获得用户信息
     * @return mixed
     */
    public function getUser(){
        if(Auth::check()){
            $user = [
                'id' => Auth::user()->id,
                'username' => Auth::user()->username,
                'avatar' => Auth::user()->avatar
            ];
            return Response::json([
                'user' => $user,
                'unreadNotifications' => Auth::user()->unreadNotifications
            ]);
        }
        return Response::json([
            'user' => false
        ]);
    }
}

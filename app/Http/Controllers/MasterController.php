<?php

namespace App\Http\Controllers;

use App\SpaceAdministration;
use App\Warship;
use Auth;
use Illuminate\Support\Facades\Redis;

use App\Discussion;
use App\Introduction;

/**
 * 主模块控制器
 * Class MasterController
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
     * 办公区
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function office(){
        return view('office/office');
    }

    /**
     * 办公区办公室选项卡
     * 舰船信息管理中心
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function warship(){
        $warships = Warship::latest()->paginate(10);
        return view('office/warship/warship',compact('warships'));
    }

    /**
     * 讨论区
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forum(){
        return view('forum/forum');
    }

    /**
     * 活动区
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function activity(){
        return view('activity/activity');
    }

    /**
     * 航天局
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function spaceAdministration(){
        $spaceAdministrations = SpaceAdministration::latest()->notDestroyed()->get();
        $count = count($spaceAdministrations);
        return view('spaceAdministration/spaceAdministration',compact('spaceAdministrations','count'));
    }

    /**
     * 制造厂
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function factory(){
        return view('factory/factory');
    }

    /**
     * 档案馆
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function archives(){
        return view('archives/archives');
    }
}

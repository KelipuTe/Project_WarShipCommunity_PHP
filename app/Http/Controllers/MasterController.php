<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\Introduction;
use Illuminate\Support\Facades\Redis;

/**
 * 这个控制器负责各个主要页面的跳转
 * Class MasterController
 * @package App\Http\Controllers
 */
class MasterController extends Controller
{
    public function welcome(){
        Redis::set('kelipute','kelipute');
        $name = Redis::get('kelipute');
        dd($name);
        return view('master/welcome');
    }

    public function about(){
        return view('master/about');
    }

    public  function office(){
        $introductions = Introduction::latest()->get();
        return view('office/office',compact('introductions'));
    }

    public  function forum(){
        $discussions = Discussion::latest()->published()->get();
        return view('forum/forum',compact('discussions'));
    }

    public  function activity(){
        return view('activity/activity');
    }

    public  function spaceAdministration(){
        return view('spaceAdministration/spaceAdministration');
    }

    public  function factory(){
        return view('factory/factory');
    }

    public  function archives(){
        return view('archives/archives');
    }
}

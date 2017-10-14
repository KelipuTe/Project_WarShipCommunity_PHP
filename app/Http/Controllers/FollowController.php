<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

/**
 * 这个控制器负责各种关注的方法
 * Class FollowController
 * @package App\Http\Controllers
 */
class FollowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['only' => ['userDiscussionFollow']]);
    }

    /**
     * user关注discussion
     * @param $discussion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userDiscussionFollow($discussion){
        Auth::user()->userDiscussionFollow($discussion);
        return back();//跳转回去
    }
}

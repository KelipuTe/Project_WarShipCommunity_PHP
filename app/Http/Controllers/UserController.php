<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\User;

/**
 * 这个控制器负责和用户
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * 注册页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function register(){
        return view('user.register');
    }

    /**
     * 用户注册后台
     * @param UserRegisterRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(UserRegisterRequest $request){
        $data = [
            'email_confirm_code'=>str_random(48),//生成48位邮箱验证码
            'avatar'=>'/image/avatar/default_avatar.jpg'
        ];
        User::create(array_merge($request->all(),$data));//创建新用户
        return redirect('/user/login');
    }

    /**
     * 登录页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login(){
        return view('user.login');
    }

    /**
     * 用户登录后台
     * @param UserLoginRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function signIn(UserLoginRequest $request){
        $data = [
            'email'=>$request->get('email'),
            'password'=>$request->get('password')
        ];
        //登录验证
        if(\Auth::attempt($data)){
            return redirect('/welcome');
        }
        //验证失败
        \Session::flash('user_login_failed','密码输入错误');
        return redirect('/user/login')->withInput();//验证失败时返回登录页面并带回数据
    }

    /**
     * 退出登录
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(){
        \Auth::logout();
        return redirect('/welcome');
    }
}

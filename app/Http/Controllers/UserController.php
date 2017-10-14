<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\User;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

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
        return view('user/register');
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
        /*登录验证*/
        if(\Auth::attempt($data)){
            return redirect('/welcome');
        }
        /*验证失败*/
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

    /**
     * 个人信息修改页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function infoEdit(){
        return view('user/infoEdit');
    }

    /**
     * 图片上传后台，包括直接上传，ajax上传，图片裁剪部分的代码
     * @param Request $request
     * @return mixed
     */
    public function avatar(Request $request){
        $file = $request->file('avatar');
        /*验证////////////////////////////////////////////////////////////////*/
        $input = array('image' => $file);//拿到上传的图片
        $rules = array(
            'image' => 'image'
        );//验证规则，图片格式
        $validator = \Validator::make($input, $rules);//检查规则是否通过
        if ( $validator->fails() ) {
            return \Response::json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }
        /*验证////////////////////////////////////////////////////////////////*/
        $destinationPath = 'uploads/';//保存上传头像的文件夹，在public目录下
        $filename = \Auth::user()->id.'_'.time().'_'.$file->getClientOriginalName();//拿到上传文件文件名
        /*文件名重命名为用户id+上传时间+文件名*/
        $file->move($destinationPath, $filename);//将上传的图片移到uploads文件夹下
        Image::make($destinationPath.$filename)->fit(400)->save();//裁剪头像，缩略图
        /*这里的Image类使用\Intervention\Image\Facades\Image*/
        /*在实现图片裁剪时这三行代码在裁剪完成后在执行///////////////////////*/
        /*$user = User::find(\Auth::user()->id);
        $user->avatar = '/'.$destinationPath.$filename;
        $user->save();*/
        /*在实现图片裁剪时这三行代码在裁剪完成后在执行///////////////////////*/
        /*使用ajax时需要返回json格式的数据*/
        return \Response::json([
            'success' => true,
            'avatar' => asset($destinationPath.$filename),
            'image' => $destinationPath.$filename
        ]);
        //return redirect('/user/info');
    }

    /**
     * 图片裁剪后台
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function cropAvatar(Request $request){
        $photo = $request->get('photo');
        $width = (int)$request->get('w');
        $height = (int)$request->get('h');
        $xAlign = (int)$request->get('x');
        $yAlign = (int)$request->get('y');
        Image::make($photo)->crop($width,$height,$xAlign,$yAlign)->save();
        $user = \Auth::user();
        $user->avatar = asset($photo);
        $user->save();
        return redirect('/user/infoEdit');
    }
}

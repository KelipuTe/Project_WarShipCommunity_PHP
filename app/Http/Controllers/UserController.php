<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use Response;
use Validator;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

use App\User;
use App\Account;
use App\ThirdPartyLibrary\Mailer\QQMailer;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;

/**
 * 用户模块控制器
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
            'email_confirm_code'=>str_random(48), // 生成48位邮箱验证码
            'api_token'=>str_random(48), // 生成48位 api_token
            'avatar'=>'/image/avatar/default_avatar.jpg' // 生成默认头像
        ];
        $status = $this->sendEmailConfirm($request->get("email"),$data['email_confirm_code']); // 发送账号激活邮件;
        $message = '很抱歉，服务器正忙，请稍后再试';
        if($status) {
            $user = User::create(array_merge($request->all(), $data)); // 创建新用户
            Account::create(['user_id' => $user->id]); // 为新用户创建对应账户
            Auth::logout(); // 用户登出
            $message = '账号注册成功，已发送一封激活邮件到您的邮箱，在登陆之前您需要激活您的账号';
        }
        return Response::json(['status' => $status, 'message' => $message]);
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
        $status = 0; $message = "密码错误或用户不存在！！！"; // 状态 0 ： 密码错误或用户不存在，登陆失败
        if(Auth::attempt($data)){
            if(Auth::user()->email_confirm == 1) {
                $status = 1; $message = "欢迎回来！！！"; // 状态 1 ：账号已激活，登陆成功
            } else {
                Auth::logout(); // 用户登出
                $status = -1; $message = "账号未激活！！！"; // 状态 -1 ： 账号未激活，登陆失败
            }
        }
        return Response::json(['status' => $status, 'message' => $message]);
    }

    /**
     * 退出登录
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(){
        Auth::logout();
        return redirect('/');
    }

    /**
     * 发送账号激活邮件
     * @param $email
     * @param $email_confirm_code
     * @return bool
     */
    public function sendEmailConfirm($email,$email_confirm_code){
        $mailer = new QQMailer(false);
        $title = "账号激活"; // 邮件标题
        $content = '<h1>Welcome to WarShipCommunity</h1>'
            . '<a href="http://localhost/user/checkEmailConfirm/' . $email . '/' . $email_confirm_code . '">点击激活您的账号</a>'; // 邮件内容
        //$mailer->addFile('image/avatar/ougen.jpg'); // 添加附件
        $status = $mailer->send($email, $title, $content); // 发送QQ邮件;
        return $status;
    }

    /**
     * 重新发送账号激活邮件
     * @param $email
     * @return bool
     */
    public function reSendEmailConfirm($email){
        $user = User::all()->where('email','=',$email)->first();
        $email_confirm_code = $user->email_confirm_code;
        $status = $this->sendEmailConfirm($email,$email_confirm_code); // 发送账号激活邮件;
        $message = '很抱歉，服务器正忙，请稍后再试';
        if($status) {
            $message = '已发送一封激活邮件到您的邮箱，在登陆之前您需要激活您的账号';
        }
        return Response::json(['status' => $status, 'message' => $message]);
    }

    /**
     * 邮箱验证码核对
     * @param $email
     * @param $emailConfirmCode
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkEmailConfirm($email,$emailConfirmCode){
        $user = User::All()->where('email',$email)->first();
        if($emailConfirmCode == $user->email_confirm_code) {
            $status = 1;
            $data = [
                'email_confirm_code'=>str_random(48),
                'email_confirm'=>1
            ];
            $user->update($data);
            return view('user/emailConfirm', compact('status','email'));
        }else{
            $status = 0;
            return view('user/emailConfirm', compact('status'));
        }
    }

    /**
     * 个人中心页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function center(){
        return view('user/center/center');
    }

    /**
     * 个人信息页面
     * @param $user_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info($user_id){
        $user = User::findOrFail($user_id);
        return view('user/center/info',compact('user'));
    }

    /**
     * 个人信息修改页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function infoEdit(){
        $user = Auth::user();
        return view('user/center/infoEdit',compact('user'));
    }

    /**
     * 个人头像修改页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function avatarEdit(){
        $user = Auth::user();
        return view('user/center/avatarEdit',compact('user'));
    }

    /**
     * 图片上传后台，ajax 上传
     * @param Request $request
     * @return mixed
     */
    public function avatar(Request $request){
        $file = $request->file('avatar'); // 拿到上传的图片
        /* ********** 验证 ********** */
        $input = array('image' => $file);
        $rules = array(
            'image' => 'image'
        ); // 验证规则，图片格式
        $validator = Validator::make($input, $rules); // 检查规则是否通过
        if ($validator->fails()){
            return Response::json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }
        /* ********** 验证 ********** */
        $destinationPath = 'uploads/avatar/'; // 保存上传头像的文件夹，在 public/uploads/avatar 目录下
        /*
         * 拿到上传文件文件名并重命名
         * 文件名重命名为用户 id + 上传时间 + 文件名
         */
        $filename = Auth::user()->id.'_'.time().'_'.$file->getClientOriginalName(); // 头像文件重命名
        $file->move($destinationPath, $filename); // 将上传的图片移到 uploads/avatar/ 文件夹下
        /*
         * 注意这里的 Image 类
         * \Intervention\Image\Facades\Image
         */
        Image::make($destinationPath.$filename)->fit(400)->save(); // 裁剪头像 400 * 400
        // 使用 ajax 时需要返回 json 格式的数据
        return Response::json([
            'success' => true,
            'avatar' => asset($destinationPath.$filename),
            'image' => $destinationPath.$filename
        ]);
    }

    /**
     * 图片裁剪后台
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function avatarCrop(Request $request){
        $photo = $request->get('photo');
        $width = (int)$request->get('w');
        $height = (int)$request->get('h');
        $xAlign = (int)$request->get('x');
        $yAlign = (int)$request->get('y');
        Image::make($photo)->crop($width,$height,$xAlign,$yAlign)->save(); // 裁剪头像
        $user = Auth::user(); // 获得当前用户
        $user->avatar = asset($photo); // 获得头像路径
        $user->save(); // 保存头像路径
        return redirect('/user/center/avatarEdit');
    }

    public function account(){
        return view('user/center/account');
    }
}
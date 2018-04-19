<?php

namespace App\Http\Controllers;

use Auth;
use Response;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\MessageRequest;
use App\Http\Requests\OfficeStoreRequest;
use App\Introduction;
use App\Message;

/**
 * 办公区模块控制器
 * Class OfficeController
 * @package App\Http\Controllers
 */
class OfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show','getIntroductions','getIntroduction','getMessages');
    }

    /**
     * 新人报道创建页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return view('office/create');
    }

    /**
     * 新人报道创建页面后台
     * @param OfficeStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(OfficeStoreRequest $request){
        $data = [
            'user_id'=>Auth::user()->id,
            'last_user_id'=>Auth::user()->id,
        ];
        $introduction = Introduction::create(array_merge($request->all(),$data));
        if($introduction != null){
            $accountController = new AccountController();
            $accountController->officeStore(Auth::user()->id); // 新人报道，增加活跃值
            $status = 1; $message = "新人报道创建成功！！！";
        } else {
            $status = 0; $message = "新人报道创建失败！！！";
        }
        return Response::json([
            'status' => $status, 'message' => $message,
            'introduction_id' => $introduction->id
        ]);
    }

    /**
     * 新人报道显示页面
     * @param $introduction_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($introduction_id){
        $introduction = Introduction::findOrFail($introduction_id);
        return view('office/show',compact('introduction'));
    }

    /**
     * 获取新人报道列表
     * @return mixed
     */
    public function getIntroductions(){
        $introductions = Introduction::latest()->paginate(5);
        return Response::json(['introductions' => $introductions]);
    }

    /**
     * 获取新人报道
     * @param $introduction_id
     * @return mixed
     */
    public function getIntroduction($introduction_id){
        $introduction = Introduction::findOrFail($introduction_id);
        return Response::json(['introduction' => $introduction,]);
    }

    /**
     * 获取新人报道回复列表
     * @param $introduction_id
     * @return mixed
     */
    public function getMessages($introduction_id){
        $introduction = Introduction::findOrFail($introduction_id);
        $messages = $introduction->messages()->latest()->paginate(10);
        return Response::json(['messages' => $messages,]);
    }

    /**
     * 新人报道显示页面迎新后台
     * @param MessageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function welcome(MessageRequest $request){
        $message = Message::create(array_merge($request->all(),['user_id'=>Auth::user()->id]));
        if($message != null){
            $accountController = new AccountController();
            $accountController->officeWelcomer(Auth::user()->id); // 新人报道欢迎者，增加活跃值
            $introduction = Introduction::findOrFail($request->get('introduction_id'));
            $accountController->officeWelcome($introduction->user->id); // 新人报道被欢迎，增加活跃值
            $status = 1; $message = "迎新成功！！！";
        } else {
            $status = 0; $message = "迎新失败！！！";
        }
        return Response::json([
            'status' => $status, 'message' => $message,
            'introduction_id' => $request->get('introduction_id')
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\IntroductionRequest;
use App\Http\Requests\MessageRequest;
use App\Introduction;
use App\Message;
use Auth;
use Response;

/**
 * Class OfficeController [办公区控制器]
 * @package App\Http\Controllers
 */
class OfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
            ->except('office','show','getIntroductions','getIntroduction','getMessages');
    }

    /**
     * 办公区
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function office(){
        return view('office/office');
    }

    /**
     * 报道创建页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return view('office/create');
    }

    /**
     * 报道创建页面后台
     * @param IntroductionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function introductionStore(IntroductionRequest $request){
        $data = [
            'user_id'=>Auth::user()->id,
            'last_user_id'=>Auth::user()->id,
        ];
        $introduction = Introduction::create(array_merge($request->all(),$data));
        $status = 0; $message = "报道失败！";
        if($introduction != null){
            $status = 1; $message = "报道成功！";
        }
        return Response::json([
            'status' => $status, 'message' => $message,
            'id' => $introduction->id
        ]);
    }

    /**
     * 报道显示页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(){
        return view('office/show');
    }

    /**
     * 获取报道列表
     * @return mixed
     */
    public function getIntroductions(){
        $introductions = Introduction::latest()->paginate(5);
        return Response::json(['introductions' => $introductions]);
    }

    /**
     * 获取报道
     * @param $id [introduction_id]
     * @return mixed
     */
    public function getIntroduction($id){
        $introduction = Introduction::find($id);
        return Response::json(['introduction' => $introduction,]);
    }

    /**
     * 获取报道回复列表
     * @param $id [introduction_id]
     * @return mixed
     */
    public function getMessages($id){
        $introduction = Introduction::find($id);
        $messages = $introduction->messages()->latest()->paginate(10);
        return Response::json(['messages' => $messages,]);
    }

    /**
     * 报道回复
     * @param MessageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function messageStore(MessageRequest $request){
        $messageStore = Message::create(array_merge($request->all(),['user_id'=>Auth::user()->id]));
        $status = 0; $message = "迎新失败！！！";
        if($message != null){
            $status = 1; $message = "迎新成功！！！";
        }
        return Response::json([
            'status' => $status, 'message' => $message,
            'messageStore' => $messageStore
        ]);
    }
}

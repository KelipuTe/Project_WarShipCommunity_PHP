<?php

namespace App\Http\Controllers;

use Auth;
use Response;

use App\Http\Requests\MessageRequest;
use App\Http\Requests\OfficeStoreRequest;
use App\Introduction;
use App\Message;

/**
 * 这个控制器负责办公区
 * Class OfficeController
 * @package App\Http\Controllers
 */
class OfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show','warship');
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
            $status = 1;
            $message = "新人报道创建成功！！！";
        } else {
            $status = 0;
            $message = "新人报道创建失败！！！";
        }
        return Response::json([
            'status' => $status,
            'message' => $message,
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

    public function getIntroduction(){
        $introductions = Introduction::latest()->paginate(10);
    }

    /**
     * 新人报道显示页面迎新后台
     * @param MessageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function welcome(MessageRequest $request){
        Message::create(array_merge($request->all(),['user_id'=>Auth::user()->id]));
        $accountController = new AccountController();
        $accountController->officeWelcomer(Auth::user()->id); // 新人报道欢迎者，增加活跃值
        $introduction = Introduction::findOrFail($request->get('introduction_id'));
        $accountController->officeWelcome($introduction->user->id); // 新人报道被欢迎，增加活跃值
        return redirect()->action('OfficeController@show',['id'=>$request->get('introduction_id')]);
    }
}

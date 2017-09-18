<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Http\Requests\OfficeStoreRequest;
use App\Introduction;
use App\Message;

class OfficeController extends Controller
{
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
            'user_id'=>\Auth::user()->id,
            'last_user_id'=>\Auth::user()->id,
        ];
        $introduction = Introduction::create(array_merge($request->all(),$data));
        return redirect()->action('OfficeController@show',['id'=>$introduction->id]);
    }

    /**
     * 新人报道显示页面
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id){
        $introduction = Introduction::findOrFail($id);
        return view('office/show',compact('introduction'));
    }

    /**
     * 新人报道修改页面
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id){
        $introduction = Introduction::findOrFail($id);
        return view('office/edit',compact('introduction'));
    }

    /**
     * 新人报道修改页面后台
     * @param OfficeStoreRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(OfficeStoreRequest $request,$id){
        $introduction = Introduction::findOrFail($id);
        $introduction->update($request->all());
        return redirect()->action('OfficeController@show',['id'=>$id]);
    }

    /**
     * 新人报道显示页面迎新后台
     * @param MessageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function welcome(MessageRequest $request){
        Message::create(array_merge($request->all(),['user_id'=>\Auth::user()->id]));
        return redirect()->action('OfficeController@show',['id'=>$request->get('introduction_id')]);
    }
}

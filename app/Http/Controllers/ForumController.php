<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Comment;
use App\Discussion;
use App\Http\Requests\CommitRequest;
use App\Http\Requests\ForumStoreRequest;

/**
 * 这个控制器负责讨论区
 * Class OfficeController
 * @package App\Http\Controllers
 */
class ForumController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
    }

    /**
     * 讨论创建页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return view('forum/create');
    }

    /**
     * 讨论创建页面后台
     * @param ForumStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ForumStoreRequest $request){
        $data = [
            'user_id'=>Auth::user()->id,
            'last_user_id'=>Auth::user()->id,
        ];
        $discussion = Discussion::create(array_merge($request->all(),$data));
        $accountController = new AccountController();
        $accountController->forumStore(Auth::user()->id); // 创建讨论，增加活跃值
        return redirect()->action('ForumController@show',['id'=>$discussion->id]);
    }

    /**
     * 讨论显示页面
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id){
        $discussion = Discussion::findOrFail($id);
        $comments = $discussion->comments()->paginate(10); // 分页
        return view('forum/show',compact('discussion','comments'));
    }

    /**
     * 讨论显示页面评论后台
     * @param CommitRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function commit(CommitRequest $request){
        Comment::create(array_merge($request->all(),['user_id'=>Auth::user()->id]));
        $discussion = Discussion::findOrFail($request->get('discussion_id'));
        $discussion->update(['last_user_id'=>Auth::user()->id]);
        $accountController = new AccountController();
        $accountController->officeWelcomer(Auth::user()->id); // 讨论评论提供者，增加活跃值
        $accountController->officeWelcome($discussion->user->id); // 讨论被评论，增加活跃值
        return redirect()->action('ForumController@show',['id'=>$request->get('discussion_id')]);
    }
}

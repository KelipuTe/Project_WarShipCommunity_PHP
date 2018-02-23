<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use Illuminate\Http\Request;

use App\Comment;
use App\Discussion;
use App\Http\Requests\CommentRequest;
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
        $this->middleware('auth')->except('show','getDiscussions','getDiscussion','getComments');
    }

    /**
     * 获得讨论列表
     * @return mixed
     */
    public function getDiscussions(){
        $discussions = Discussion::latest()->published()->paginate(10);
        return Response::json([
            'discussions' => $discussions,
        ]);
    }

    /**
     * 获得讨论内容
     * @param $discussion_id
     * @return mixed
     */
    public function getDiscussion($discussion_id){
        $discussion = Discussion::findOrFail($discussion_id);
        $isUser = false;
        if(Auth::user()){
            if(Auth::user()->id == $discussion->user_id) {
                $isUser = true;
            }
        }
        return Response::json([
            'discussion' => $discussion,
            'isUser' => $isUser
        ]);
    }

    /**
     * 获得讨论评论列表
     * @param $discussion_id
     * @return mixed
     */
    public function getComments($discussion_id){
        $discussion = Discussion::findOrFail($discussion_id);
        $comments = $discussion->comments()->latest()->paginate(10);
        return Response::json([
            'comments' => $comments,
        ]);
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(){
        return view('forum/show');
    }

    /**
     * 讨论显示页面评论后台
     * @param CommentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function comment(CommentRequest $request){
        $comment = Comment::create(array_merge($request->all(),['user_id'=>Auth::user()->id]));
        if($comment != null){
            $discussion = Discussion::findOrFail($request->get('discussion_id'));
            $discussion->update(['last_user_id'=>Auth::user()->id]);
            $accountController = new AccountController();
            $accountController->officeWelcomer(Auth::user()->id); // 讨论评论提供者，增加活跃值
            $accountController->officeWelcome($discussion->user->id); // 讨论被评论，增加活跃值
            $status = 1;
            $message = "评论创建成功！！！";
        } else {
            $status = 0;
            $message = "评论创建失败！！！";
        }
        return Response::json([
            'status' => $status,
            'message' => $message
        ]);
    }
}

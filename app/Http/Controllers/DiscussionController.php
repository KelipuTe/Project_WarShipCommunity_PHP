<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Discussion;
use App\Events\AddLiveness;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ForumStoreRequest;

use App\Events\HotDiscussion;
use App\Events\NiceComment;
use App\Events\NiceDiscussion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;
use Auth;
use Response;

/**
 * 讨论区模块控制器
 * Class OfficeController
 * @package App\Http\Controllers
 */
class DiscussionController extends Controller
{
    public function __construct(){
        $this->middleware('auth')
            ->except('discussion','show','getDiscussion', 'getComments');
    }

    /**
     * 讨论区
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function discussion(){
        return view('discussion/discussion');
    }

    /**
     * 讨论创建页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return view('discussion/create');
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
        if($discussion != null){
            Redis::command('HSET', ['warshipcommunity:discussion:viewcount',$discussion->id,0]);
            Redis::command('HSET', ['warshipcommunity:discussion:nicecount',$discussion->id,0]);
            $status = 1; $message = "讨论创建成功！！！";
            event(new AddLiveness($discussion->user_id,'discussionStore')); // 创建讨论，增加活跃值
        } else {
            $status = 0; $message = "讨论创建失败！！！";
        }
        return Response::json([
            'status' => $status, 'message' => $message,
            'discussion_id' => $discussion->id
        ]);
    }

    /**
     * 讨论显示页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(){
        return view('discussion/show');
    }

    /**
     * 获得讨论内容
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function getDiscussion($id,Request $request){
        $discussion = Discussion::findOrFail($id);
        $ip = $request->ip(); // 获取 ip 地址
        event(new HotDiscussion($discussion,$ip)); // 触发访问 discussion 事件
        $hot_discussion = Redis::hget('warshipcommunity:discussion:viewcount',$id); // 获取 redis 数据库中的点击量
        $nice_discussion = Redis::hget('warshipcommunity:discussion:nicecount',$id); // 获取 redis 数据库中的推荐量
        // 确认用户是否推荐讨论
        $isNice = false;
        if(Auth::check()) {
            $existsInRedisSet = Redis::command('SISMEMBER', ['warshipcommunity:discussion:nicelimit:' . $id, Auth::user()->id]);
            if ($existsInRedisSet) {
                $isNice = true;
            }
        }
        // 确认用户是否是否是讨论发起人
        $isOwner = Gate::allows('discussionDelete',$discussion);
        return Response::json([
            'discussion' => $discussion,
            'hot_discussion' => $hot_discussion,
            'nice_discussion' => $nice_discussion,
            'isNice' => $isNice,
            'isOwner' => $isOwner
        ]);
    }

    /**
     * 讨论显示页面评论后台
     * @param CommentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function comment(CommentRequest $request){
        $comment = Comment::create(array_merge($request->all(),['user_id'=>Auth::user()->id]));
        $status = 0; $message = "评论创建失败！！！";
        if($comment != null){
            $discussion = Discussion::findOrFail($request->get('discussion_id'));
            $discussion->update(['last_user_id'=>Auth::user()->id]);
            $status = 1; $message = "评论创建成功！！！";
            event(new AddLiveness(Auth::user()->id,'discussionCommit')); // 讨论回复者，增加活跃值
            event(new AddLiveness($discussion->user_id,'discussionCommit')); // 讨论被回复，增加活跃值
        }
        return Response::json(['status' => $status, 'message' => $message]);
    }

    /**
     * 获得讨论回复列表
     * @param $id
     * @return mixed
     */
    public function getComments($id){
        $discussion = Discussion::findOrFail($id);
        $comments = $discussion->comments()->oldest()->blacklist()->paginate(5);
        return Response::json([
            'isLogin' => Auth::check(),
            'comments' => $comments
        ]);
    }

    /**
     * 推荐讨论
     * @param $id [discussion_id]
     * @return mixed
     */
    public function niceDiscussion($id){
        $discussion = Discussion::findOrFail($id);
        $user_id = Auth::user()->id;
        $userNiceDiscussionKey = 'warshipcommunity:discussion:nicelimit:'.$id; // 从 redis 数据库中获取信息
        $existsInRedisSet = Redis::command('SISMEMBER', [$userNiceDiscussionKey, $user_id]); // 确认是否已经推荐
        $status = -1; $message = '请不要重复推荐';
        if(!$existsInRedisSet){
            event(new NiceDiscussion($discussion,$user_id)); // 触发 discussion 推荐事件
            $existsInRedisSet = Redis::command('SISMEMBER', [$userNiceDiscussionKey, $user_id]); // 确认是否推荐成功
            $status = 1; $message = '推荐成功';
            if(!$existsInRedisSet){
                $status = 0; $message = '推荐失败';
            }
        }
        return Response::json(['status' => $status, 'message' => $message]);
    }

    /**
     * 回复点赞
     * @param $id [comment_id]
     * @return
     */
    public function niceComment($id){
        $comment = Comment::findOrFail($id);
        $user_id = Auth::user()->id;
        $userNiceCommentKey = 'warshipcommunity:comment:nicelimit:'.$id; // 从 redis 数据库中获取信息
        $existsInRedisSet = Redis::command('SISMEMBER', [$userNiceCommentKey, $user_id]); // 确认是否已经推荐
        $status = -1; $message = '请不要重复点赞';
        if(!$existsInRedisSet){
            event(new NiceComment($comment,$user_id)); // 触发 comment 点赞事件
            $existsInRedisSet = Redis::command('SISMEMBER', [$userNiceCommentKey, $user_id]); // 确认是否推荐成功
            $status = 1; $message = '点赞成功';
            if(!$existsInRedisSet){
                $status = 0; $message = '点赞失败';
            }
        }
        return Response::json([
            'status' => $status, 'message' => $message,
            'comment_id' => $id,
            'cache_nice_comment' => Redis::hget('warshipcommunity:comment:nicecount',$id)
        ]);
    }

    /**
     * 讨论软删除
     * @param $discussion_id
     * @return mixed
     */
    public function softdelete($discussion_id){
        $discussion = Discussion::findOrFail($discussion_id);
        $status = $discussion->delete();
        $message = "爆破失败";
        if($status) {
            $message = "爆破成功";
        }
        return Response::json(['status' => $status, 'message' => $message]);
    }

    /**
     * 讨论置顶
     * @param Request $request
     * @return mixed
     */
    public function setTop(Request $request){
        $accountController = new AccountController();
        if($accountController->useTool('setTop')){
            $discussion = Discussion::find($request->input('target'));
            $discussion->update(['set_top' => true]);
            return Response::json(['status' => 'success']);
        }
        return Response::json([
            'status' => 'failed',
            'message' => '道具库存为0'
        ]);
    }
}

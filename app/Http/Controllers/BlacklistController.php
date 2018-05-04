<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use App\Blacklist;
use App\Comment;
use App\Discussion;
use Illuminate\Http\Request;

/**
 * 黑名单控制器
 * Class BlacklistController
 * @package App\Http\Controllers
 */
class BlacklistController extends Controller
{
    /**
     * BlacklistController constructor.
     */
    public function __construct()
    {
        $this->middleware('admin')->except(['notice','report','getDoneBlacklists']);
    }

    /**
     * 黑名单公告牌
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notice(){
        return view('office/blacklist/notice');
    }

    /**
     * 黑名单档案室
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function archives(){
        return view('office/blacklist/report');
    }

    /**
     * 用户举报
     * @param Request $request
     * @return mixed
     */
    public function report(Request $request){
        $data = [
            'explain' => '违规',
            'user_id' => Auth::user()->id
        ];
        Blacklist::create(array_merge($request->all(),$data));
        return Response::json(['status' => 1, 'message' => '成功']);
    }

    /**
     * 获得没有处理的举报条目
     * @return mixed
     */
    public function getBlacklists(){
        $blacklists = Blacklist::done(false)->latest()->paginate(10);
        return Response::json(['blacklists' => $blacklists]);
    }

    /**
     * 获得已经处理的举报条目
     * @return mixed
     */
    public function getDoneBlacklists(){
        $blacklists = Blacklist::done(true)->latest()->paginate(10);
        return Response::json(['blacklists' => $blacklists]);
    }

    /**
     * 锁定被举报条目的具体内容所在地
     * @param Request $request
     * @return mixed
     */
    public function locking(Request $request){
        $blacklist = Blacklist::findOrFail($request->get('blacklist_id'));
        switch ($blacklist->type){
            case 'discussion':
                $url = '/discussion/show/'.$blacklist->target;
                break;
            case 'comment':
                $comment = Comment::findOrFail($blacklist->target);
                $url = '/discussion/show/'.$comment->discussion_id;
                break;
        }
        return Response::json(['url' => $url]);
    }

    /**
     * 同意举报条目的意见
     * @param Request $request
     * @return mixed
     */
    public function agree(Request $request){
        $blacklist = Blacklist::findOrFail($request->get('blacklist_id'));
        switch ($blacklist->type){
            case 'discussion':
                $target = Discussion::findOrFail($blacklist->target);
                break;
            case 'comment':
                $target = Comment::findOrFail($blacklist->target);
                break;
            default:
                $target = null;
        }
        if($target != null) {
            $target->blacklist = true;
            $target->save();
            $message = '处理成功';
            $blacklist->done = true;
            $blacklist->save();
        } else {
            $message = '处理失败';
        }
        return Response::json(['message' => $message]);
    }
}

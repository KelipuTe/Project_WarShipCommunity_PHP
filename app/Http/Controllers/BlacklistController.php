<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use App\Blacklist;
use App\Comment;
use App\Discussion;
use Illuminate\Http\Request;

/**
 * Class BlacklistController [黑名单控制器]
 * @package App\Http\Controllers
 */
class BlacklistController extends Controller
{
    /**
     * BlacklistController constructor.
     */
    public function __construct()
    {
        $this->middleware('admin')
            ->except(['notice','report','getDoneBlacklists']);
    }

    /**
     * 公告牌
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notice(){
        return view('office/blacklist/notice');
    }

    /**
     * 档案室
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function archives(){
        return view('office/blacklist/report');
    }

    /**
     * 举报
     * @param Request $request
     * @return mixed
     */
    public function report(Request $request){
        Blacklist::create(array_merge($request->all(),['user_id' => Auth::user()->id]));
        return Response::json(['status' => 1, 'message' => '举报成功，请等待管理员处理']);
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
     * 查阅被举报条目的具体内容
     * @param Request $request
     * @return mixed
     */
    public function locking(Request $request){
        $blacklist = Blacklist::find($request->get('blacklist_id'));
        switch ($blacklist->type){
            case 'discussion':
                $url = '/discussion/show/'.$blacklist->target;
                break;
            case 'comment':
                $comment = Comment::find($blacklist->target);
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
        $blacklist = Blacklist::find($request->get('blacklist_id'));
        switch ($blacklist->type){
            case 'discussion':
                $target = Discussion::find($blacklist->target);
                break;
            case 'comment':
                $target = Comment::find($blacklist->target);
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

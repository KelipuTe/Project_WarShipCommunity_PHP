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
            ->except(['notice','report','getDoneBlacklists','locking']);
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
        $blacklists = Blacklist::latest()->paginate(10);
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
     * @return mixed
     */
    public function locking(){
        $blacklist = Blacklist::find(request('id'));
        switch ($blacklist->type){
            case 'discussion':
                $target = Discussion::find($blacklist->target);break;
            case 'comment':
                $target = Comment::find($blacklist->target);break;
            default:
                $target = null;
        }
        return Response::json([
            'blacklist' => $blacklist,
            'target' => $target
        ]);
    }

    /**
     * 同意举报条目的意见
     * @param Request $request
     * @return mixed
     */
    public function handel(Request $request){
        $blacklist = Blacklist::find($request->get('id'));
        switch ($blacklist->type){
            case 'discussion':
                $target = Discussion::find($blacklist->target);break;
            case 'comment':
                $target = Comment::find($blacklist->target);break;
            default:
                $target = null;
        }
        $status = 0; $message = '处理失败';
        if($target != null) {
            switch($request->get('opinion')){
                case 'agree':
                    $target->blacklist = true;
                    $blacklist->done = true;
                    $blacklist->admin_opinion = '违规！';
                    break;
                case 'disagree':
                    $blacklist->done = true;
                    $blacklist->admin_opinion = '理由不充分！';
                    break;
            }
            $target->save();
            $blacklist->save();
            $status = 1; $message = '处理成功';
        }
        return Response::json(['status' => $status,'message' => $message]);
    }
}

<?php

namespace App\Http\Controllers;

use Response;
use App\Account;
use App\Discussion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 这个控制器负责对用户账户的修改
 * Class AccountController
 * @package App\Http\Controllers
 */
class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('getLiveness');
    }

    /**
     * 新人报道，增加活跃值
     * @param $user_id
     */
    public function officeStore($user_id){
        $this->update($user_id,Account::$livenessScore['officeStore']);
    }

    /**
     * 新人报道被欢迎，增加活跃值
     * @param $user_id
     */
    public function officeWelcome($user_id){
        $this->update($user_id,Account::$livenessScore['officeWelcome']);
    }

    /**
     * 新人报道欢迎者，增加活跃值
     * @param $user_id
     */
    public function officeWelcomer($user_id){
        $this->update($user_id,Account::$livenessScore['officeWelcomer']);
    }

    /**
     * 创建讨论，增加活跃值
     * @param $user_id
     */
    public function forumStore($user_id){
        $this->update($user_id,Account::$livenessScore['forumStore']);
    }

    /**
     * 讨论被评论，增加活跃值
     * @param $user_id
     */
    public function forumCommit($user_id){
        $this->update($user_id,Account::$livenessScore['forumCommit']);
    }

    /**
     * 讨论评论提供者，增加活跃值
     * @param $user_id
     */
    public function forumCommitter($user_id){
        $this->update($user_id,Account::$livenessScore['forumCommitter']);
    }

    /**
     * 签到增加活跃值
     * @param $user_id
     * @param $power
     */
    public function activitySign($user_id,$power = 1){
        $this->update($user_id,$power * Account::$livenessScore['activitySign']); // $power 表示签到活跃值倍率
    }

    /**
     * 活跃值数据库操作函数
     * @param $user_id
     * @param $num
     */
    public function update($user_id,$num){
        $user = DB::table('accounts')->select('id')->where('user_id',$user_id)->first();
        $account = Account::findOrFail($user->id);
        $account->update(['liveness'=>$account->liveness += $num]);
    }

    /**
     * 获得用户活跃值和等级
     * @param $user_id
     * @return mixed
     */
    public function getLivenessAndLevel($user_id){
        $discussion = Discussion::find($user_id);
        $liveness = $discussion->user->account->liveness;
        $level = $discussion->user->account->level($liveness);
        return Response::json([
            'liveness' => $liveness,
            'level' => $level
        ]);
    }
}

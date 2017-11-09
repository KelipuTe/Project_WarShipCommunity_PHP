<?php

namespace App\Http\Controllers;

use App\Account;
use App\Discussion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 整个控制器负责对用户账户的修改
 * Class AccountController
 * @package App\Http\Controllers
 */
class AccountController extends Controller
{

    /**
     * 新人报道，增加活跃值
     * @param $id
     */
    public function officeStore($id){
        $this->update($id,Account::$livenessScore['officeStore']);
    }

    /**
     * 新人报道被欢迎，增加活跃值
     * @param $id
     */
    public function officeWelcome($id){
        $this->update($id,Account::$livenessScore['forumWelcome']);
    }

    /**
     * 新人报道欢迎者，增加活跃值
     * @param $id
     */
    public function officeWelcomer($id){
        $this->update($id,Account::$livenessScore['forumWelcomer']);
    }

    /**
     * 创建讨论，增加活跃值
     * @param $id
     */
    public function forumStore($id){
        $this->update($id,Account::$livenessScore['forumStore']);
    }

    /**
     * 讨论被评论，增加活跃值
     * @param $id
     */
    public function forumCommit($id){
        $this->update($id,Account::$livenessScore['forumCommit']);
    }

    /**
     * 讨论评论提供者，增加活跃值
     * @param $id
     */
    public function forumCommitter($id){
        $this->update($id,Account::$livenessScore['forumCommitter']);
    }

    /**
     * 活跃值数据库操作函数
     * @param $id
     * @param $num
     */
    public function update($id,$num){
        $user = DB::table('accounts')->select('id')->where('user_id',$id)->first();
        $account = Account::findOrFail($user->id);
        $account->update(['liveness'=>$account->liveness += $num]);
    }

    public function getLiveness($id){
        $discussion = Discussion::find($id);
        $liveness = $discussion->user->account->liveness;
        $level = $discussion->user->account->level($liveness);
        return \Response::json([
            'liveness' => $liveness,
            'level' => $level
        ]);
    }
}

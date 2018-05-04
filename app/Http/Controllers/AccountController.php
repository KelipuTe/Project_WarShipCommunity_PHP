<?php

namespace App\Http\Controllers;

use App\Tool;
use Response;
use Auth;
use App\Account;
use App\Discussion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 用户账户控制器
 * Class AccountController
 * @package App\Http\Controllers
 */
class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('getLivenessAndLevel');
    }

    /**
     * 新人报道，增加活跃值
     * @param $user_id
     */
    public function officeStore($user_id){
        $this->update($user_id,Account::$livenessScore['officeStore']);
        $this->addBonusPoints($user_id,Account::$livenessScore['officeStore']);
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
        $this->addBonusPoints($user_id,Account::$livenessScore['officeWelcomer']);
    }

    /**
     * 创建讨论，增加活跃值
     * @param $user_id
     */
    public function forumStore($user_id){
        $this->update($user_id,Account::$livenessScore['forumStore']);
        $this->addBonusPoints($user_id,Account::$livenessScore['forumStore']);
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
        $this->addBonusPoints($user_id,Account::$livenessScore['forumCommitter']);
    }

    /**
     * 签到增加活跃值
     * @param $user_id
     * @param $power
     */
    public function activitySign($user_id,$power = 1){
        $this->update($user_id,$power * Account::$livenessScore['activitySign']); // $power 表示签到活跃值倍率
        $this->addBonusPoints($user_id,$power * Account::$livenessScore['activitySign']);
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

    public function addBonusPoints($user_id,$num){
        $user = DB::table('accounts')->select('id')->where('user_id',$user_id)->first();
        $account = Account::findOrFail($user->id);
        $account->update(['bonus_points'=>$account->bonus_points += $num]);
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

    public function getTools(){
        $tools = Tool::where('user_id','=',Auth::user()->id)->first();
        if($tools == null || $tools == ''){
            $setTop = [
                'name' => 'setTop',
                'user_id' =>  Auth::user()->id
            ];
            Tool::create($setTop);
            $reSign = [
                'name' => 'reSign',
                'user_id' =>  Auth::user()->id
            ];
            Tool::create($reSign);
        }
        $tools = Tool::where('user_id','=',Auth::user()->id)->get();
        return Response::json([
            'account' => Account::where('user_id','=',Auth::user()->id)->first(),
            'tools' => $tools
        ]);
    }

    public function useTool($name){
        $tool = Tool::where('user_id','=',Auth::user()->id)->where('name','=',$name)->first();
        if($tool->number > 0){
            $tool->update(['number' => $tool->number - 1]);
            return true;
        }
        return false;
    }

    public function exchangeTool(Request $request){
        $account = Account::where('user_id','=',Auth::user()->id)->first();
        $tool = Tool::where('user_id','=',Auth::user()->id)->where('name','=',$request->input('name'))->first();
        switch($tool->name){
            case 'setTop':
                $bonus_points = 50; break;
            case 'reSign':
                $bonus_points = 30; break;
            default:
                $bonus_points = 0;
        }
        if($bonus_points != 0 && ($account->bonus_points - $bonus_points >= 0)){
            $account->update(['bonus_points' => ($account->bonus_points - $bonus_points)]);
            $tool->update(['number' => $tool->number + 1]);
            $tools = Tool::where('user_id','=',Auth::user()->id)->get();
            return Response::json([
                'status' => 'success',
                'account' => $account,
                'tools' => $tools
            ]);
        }
        return Response::json([
            'status' => 'failed',
            'message' => '积分不够'
        ]);
    }
}

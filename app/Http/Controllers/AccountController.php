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
    public function __construct(){
        $this->middleware('auth')
            ->except('getLivenessAndLevel');
    }

    /**
     * 获得用户活跃值和等级
     * @param $user_id
     * @return mixed
     */
    public function getLivenessAndLevel($user_id){
        $discussion = Discussion::find($user_id);
        $liveness = $discussion->user->account->liveness;
        $level = $discussion->user->account->levelCalculate($liveness);
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

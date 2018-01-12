<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 用户账户类
 * Class Account
 * @package App
 */
class Account extends Model
{
    protected $fillable=['user_id'];

    /**
     * 活跃值
     * 命名方式为"区域名" + "区域控制器方法名"
     * @var array
     */
    public static $livenessScore = array(
        'officeStore'=>50, 'officeWelcome'=>5, 'officeWelcomer'=>5,
        'forumStore'=>10, 'forumCommit'=>5, 'forumCommitter'=>5,
        'activitySign'=>2
    );

    /**
     * 活跃值等级
     * @var array
     */
    public $levelScore = array(
        'level_1'=>100, 'level_2'=>200, 'level_3'=>300,
        'level_4'=>500, 'level_5'=>800, 'level_6'=>1300,
        'level_7'=>2100, 'level_8'=>3400, 'level_9'=>5500,
        'level_10'=>8900, 'level_11'=>14400, 'level_12'=>23300,
        'level_13'=>37700, 'level_14'=>61000, 'level_15'=>98700,
        'level_16'=>159700, 'level_17'=>258400, 'level_18'=>418100
    );

    /**
     * 判断活跃值对应等级
     * @param $liveness
     * @return int
     */
    public function level($liveness){
        for($i = 1;$i < 19;++$i){
            if($liveness < $this->levelScore['level_'.$i]){
                break;
            }
        }
        return $i;
    }

    /**
     * 通过 account 找到 user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}

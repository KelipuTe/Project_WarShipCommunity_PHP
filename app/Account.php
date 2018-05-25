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
    protected $table = 'accounts';

    protected $fillable=['user_id'];

    protected $appends = ['relatedInfo'];

    public function getRelatedInfoAttribute(){
        $level = $this->levelCalculate($this->attributes['liveness']);
        $nextLevel = $this->levelScore['level_'.($level + 1)];
        $data = [
            'level' => $level,
            'nextLevel' => $nextLevel,
        ];
        return $data;
    }

    /**
     * 活跃值，命名方式为"区域名"+"事件名"
     * @var array
     */
    protected $livenessScore = array(
        'officeStore'=>10, 'officeWelcome'=>5,
        'discussionStore'=>10, 'discussionCommit'=>5,
        'activitySign'=>5
    );

    /**
     * 活跃值等级，斐波那契数列
     * @var array
     */
    protected $levelScore = array(
        'level_1'=>100, 'level_2'=>200, 'level_3'=>300,
        'level_4'=>500, 'level_5'=>800, 'level_6'=>1300,
        'level_7'=>2100, 'level_8'=>3400, 'level_9'=>5500,
        'level_10'=>8900, 'level_11'=>14400, 'level_12'=>23300,
        'level_13'=>37700, 'level_14'=>61000, 'level_15'=>98700,
        'level_16'=>159700, 'level_17'=>258400, 'level_18'=>418100
    );

    /**
     * 增加活跃值
     * @param $type
     * @param int $power
     */
    public function addLiveness($type,$power = 1){
        $this->update([
            'liveness'=> $this->liveness += ($power * $this->livenessScore[$type])
        ]);
        $this->addBonusPoints($type);
    }

    /**
     * 增加积分
     * @param $type
     */
    public function addBonusPoints($type){
        $this->update([
            'bonus_points'=>$this->bonus_points += ($this->livenessScore[$type]/5)
        ]);
    }

    /**
     * 计算活跃值对应等级
     * @param $liveness
     * @return int
     */
    public function levelCalculate($liveness){
        for($level = 1;$level < 19;++$level){
            if($liveness < $this->levelScore['level_'.$level]){
                break;
            }
        }
        return $level;
    }

    /**
     * 通过 account 找到 user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 卫星类
 * Class Satellite
 * @package App
 */
class Satellite extends Model
{
    protected $table = 'satellites';

    protected $fillable = ['title','body','user_id'];

    protected $appends = ['username'];

    public function getUsernameAttribute(){
        return $this->user()->get(['username']);
    }

    /**
     * 可以将 published_at 变量转换为 Carbon 类型
     * @var array
     */
    protected $dates = ['destroyed_at'];

    /**
     * 通过 spaceAdministration 找到 user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * 筛选未被击落的卫星
     * @param $query
     */
    public function scopeNotDestroyed($query){
        $query -> where('ontrack','=',1);
    }
}

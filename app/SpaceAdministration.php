<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 卫星类
 * Class SpaceAdministration
 * @package App
 */
class SpaceAdministration extends Model
{
    protected $table = 'space_administrations';

    protected $fillable = ['title','body','user_id'];

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

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 签到类
 * Class Sign
 * @package App
 */
class Sign extends Model
{
    protected $table = 'signs';

    protected $fillable = ['user_id','year','month','day'];

    /**
     * 通过Sign找到User
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}

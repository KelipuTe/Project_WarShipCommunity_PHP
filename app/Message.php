<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 简介回复类
 * Class Message
 * @package App
 */
class Message extends Model
{
    protected $fillable = [
        'body','user_id','introduction_id'
    ];

    /**
     * 通过 message 找到 user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * 通过 message 找到 introduction
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function introduction(){
        return $this->belongsTo(Introduction::class);
    }
}

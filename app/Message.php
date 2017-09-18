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

    public function user(){
        return $this->belongsTo(User::class);//通过message拿到user
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 简介类
 * Class Introduction
 * @package App
 */
class Introduction extends Model
{
    protected $fillable = [
        'title','body','user_id','last_user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);//通过introduction拿到user
    }

    public function messages(){
        return $this->hasMany(Message::class);//通过introduction拿到message
    }
}

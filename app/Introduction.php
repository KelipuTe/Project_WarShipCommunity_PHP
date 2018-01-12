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

    /**
     * 通过 introduction 找到 user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * 通过 introduction 找到所有的 message
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(){
        return $this->hasMany(Message::class);
    }
}

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
    /**
     * 模型对应的数据表
     * @var string
     */
    protected $table = 'introductions';

    /**
     * 声明可批量赋值的数据
     * @var array
     */
    protected $fillable = [
        'title','body','user_id','last_user_id'
    ];

    /**
     * 声明向模型中添加的数据
     * @var array
     */
    protected $appends = ['username','user_avatar','count_messages'];

    /**
     * 获得 username 用户名
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsernameAttribute(){
        return $this->user()->get(['username']);
    }

    /**
     * 获得 user 头像
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserAvatarAttribute(){
        return $this->user()->get(['avatar']);
    }

    /**
     * 获得 message 数量
     * @return int
     */
    public function getCountMessagesAttribute(){
        return $this->messages()->count();
    }

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

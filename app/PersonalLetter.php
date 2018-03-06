<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 私信类
 * Class PersonalLetter
 * @package App
 */
class PersonalLetter extends Model
{
    protected $table = 'personal_letters';

    protected $fillable = ['from_user_id','to_user_id','body'];

    /**
     * 声明向模型中添加的数据
     * @var array
     */
    protected $appends = ['from_user_username','to_user_username'];

    /**
     * 获得 from_user_username 用户名
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFromUserUsernameAttribute(){
        $user = User::findOrFail($this->attributes['from_user_id']);
        $user_username = $user->username;
        return $user_username;
    }

    /**
     * 获得 to_user_username 用户名
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getToUserUsernameAttribute(){
        $user = User::findOrFail($this->attributes['to_user_id']);
        $user_username = $user->username;
        return $user_username;
    }

    /**
     * 通过 personalLetter 找到发送私信的 user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromUser(){
        return $this->belongsTo(User::class,'from_user_id');
    }

    /**
     * 通过 personalLetter 找到接收私信的 user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toUser(){
        return $this->belongsTo(User::class,'to_user_id');
    }
}

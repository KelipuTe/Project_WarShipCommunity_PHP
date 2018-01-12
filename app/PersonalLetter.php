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
    protected $table = 'personal_letter';

    protected $fillable = ['from_user_id','to_user_id','title','body'];

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

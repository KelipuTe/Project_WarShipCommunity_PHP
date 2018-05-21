<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PersonalLetter [私信类]
 * @package App
 */
class PersonalLetter extends Model
{
    protected $table = 'personal_letters';

    protected $fillable = ['from_user_id','to_user_id','body'];

    protected $appends = ['from_user_info','to_user_info'];

    /**
     * @return array
     */
    public function getFromUserInfoAttribute(){
        $username = $this->fromUser()->get(['username']);
        $avatar = $this->fromUser()->get(['avatar']);
        $data = [
            'username' => $username[0]->username,
            'avatar' => $avatar[0]->avatar,
        ];
        return $data;
    }

    /**
     * @return array
     */
    public function getToUserInfoAttribute(){
        $username = $this->toUser()->get(['username']);
        $avatar = $this->toUser()->get(['avatar']);
        $data = [
            'username' => $username[0]->username,
            'avatar' => $avatar[0]->avatar,
        ];
        return $data;
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

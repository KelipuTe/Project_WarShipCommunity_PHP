<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factory extends Model
{
    protected $fillable = [
        'title','introduction','point','plain','type','size','user_id','satellite','satellite_id'
    ];

    protected $appends = ['userInfo'];

    public function getUserInfoAttribute(){
        $id = $this->user()->get(['id']);
        $username = $this->user()->get(['username']);
        $avatar = $this->user()->get(['avatar']);
        $data = [
            'id' => $id[0]->id,
            'username' => $username[0]->username,
            'avatar' => $avatar[0]->avatar,
        ];
        return $data;
    }

    /**
     * 通过 Factory 找到 user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}

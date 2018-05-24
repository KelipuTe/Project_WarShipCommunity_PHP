<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * 简介类
 * Class Introduction
 * @package App
 */
class Introduction extends Model
{

    protected $table = 'introductions';

    protected $fillable = [
        'title','body','user_id','last_user_id'
    ];

    protected $appends = ['relatedInfo'];

    public function getRelatedInfoAttribute(){
        $username = $this->user()->get(['username']);
        $avatar = $this->user()->get(['avatar']);
        $lastUser = User::find($this->attributes['last_user_id']);
        $update_diff = Carbon::parse($this->attributes['updated_at']);
        $data = [
            'username' => $username[0]->username,
            'avatar' => $avatar[0]->avatar,
            'lastUsername' => $lastUser->username,
            'countMessages' => $this->messages()->count(),
            'update_diffForHumans' => $update_diff->diffForHumans()
        ];
        return $data;
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

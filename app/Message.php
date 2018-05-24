<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * 简介回复类
 * Class Message
 * @package App
 */
class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'body','user_id','introduction_id'
    ];

    protected $appends = ['relatedInfo'];

    public function getRelatedInfoAttribute(){
        $username = $this->user()->get(['username']);
        $avatar = $this->user()->get(['avatar']);
        $update_diff = Carbon::parse($this->attributes['updated_at']);
        $data = [
            'username' => $username[0]->username,
            'avatar' => $avatar[0]->avatar,
            'update_diffForHumans' => $update_diff->diffForHumans()
        ];
        return $data;
    }

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

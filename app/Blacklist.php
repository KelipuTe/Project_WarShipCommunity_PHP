<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 黑名单类
 * Class Blacklist
 * @package App
 */
class Blacklist extends Model
{
    protected $table = 'blacklists';

    protected $fillable = [
        'type', 'target','explain','user_id'
    ];

    protected $appends = ['relatedInfo'];

    public function getRelatedInfoAttribute(){
        $username = $this->user()->get(['username']); // 举报的用户名
        switch ($this->attributes['type']){
            case 'discussion':
                $target = Discussion::find($this->attributes['target']);break;
            case 'comment':
                $target = Comment::find($this->attributes['target']);break;
            default:
                $target = null;
        }
        $target_username = $target->user()->get(['username']); // 被举报的用户名
        $data = [
            'username' => $username[0]->username,
            'target_username' => $target_username[0]->username
        ];
        return $data;
    }

    /**
     * 判断是否处理过该项举报
     * @param $query
     * @param $isDone
     */
    public function scopeDone($query,$isDone){
        $query -> where('done','=',$isDone);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}

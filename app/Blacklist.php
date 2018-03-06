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

    /**
     * 判断是否处理过该项举报
     * @param $query
     * @param $isDone
     */
    public function scopeDone($query,$isDone){
        $query -> where('done','=',$isDone);
    }
}

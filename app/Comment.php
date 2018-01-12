<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 讨论评论类
 * Class Comment
 * @package App
 */
class Comment extends Model
{
    protected $fillable = [
        'body','user_id','discussion_id'
    ];

    /**
     * 通过 comment 找到 user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * 通过 comment 找到 discussion
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discussion(){
        return $this->belongsTo(Discussion::class);
    }
}

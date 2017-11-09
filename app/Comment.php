<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 文章评论类
 * Class Comment
 * @package App
 */
class Comment extends Model
{
    protected $fillable = [
        'body','user_id','discussion_id'
    ];

    /**
     * 通过comment找到user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * 通过comment找到discussion
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discussion(){
        return $this->belongsTo(Discussion::class);
    }
}

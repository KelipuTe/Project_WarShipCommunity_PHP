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
    protected $table = 'comments';

    protected $fillable = [
        'body','user_id','discussion_id'
    ];

    /**
     * 声明向模型中添加的数据
     * @var array
     */
    protected $appends = ['username','user_avatar'];

    /**
     * 获得 username 用户名
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsernameAttribute(){
        return $this->user()->get(['username']);
    }

    /**
     * 获得 user 头像
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserAvatarAttribute(){
        return $this->user()->get(['avatar']);
    }

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

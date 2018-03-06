<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Support\Facades\Redis;

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
    protected $appends = ['username','user_avatar','cache_nice_comment','is_nice'];

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
     * 获得缓存的点赞数
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCacheNiceCommentAttribute(){
        $comment_id = $this->attributes['id'];
        $cache_nice_comment = Redis::hget('warshipcommunity:comment:nicecount',$comment_id); // 获取 redis 数据库中的推荐量
        if($cache_nice_comment == null){
            $cache_nice_comment = $this->attributes['nice_comment'];
        }
        return $cache_nice_comment;
    }

    /**
     * 判断用户是否已经点赞
     * @return bool
     */
    public function getIsNiceAttribute(){
        $comment_id = $this->attributes['id'];
        $is_nice = false;
        if(Auth::check()) {
            $existsInRedisSet = Redis::command('SISMEMBER', ['warshipcommunity:comment:nicelimit:' . $comment_id, Auth::user()->id]);
            if ($existsInRedisSet) {
                $is_nice = true;
            }
        }
        return $is_nice;
    }

    /**
     * 筛选在黑名单内的讨论
     * @param $query
     */
    public function scopeBlacklist($query){
        $query -> where('blacklist','!=',true);
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

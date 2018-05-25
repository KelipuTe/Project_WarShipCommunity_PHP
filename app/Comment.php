<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Support\Facades\Redis;

/**
 * Class Comment [讨论回复类]
 * @package App
 */
class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'body','user_id','discussion_id'
    ];

    protected $appends = ['relatedInfo'];

    public function getRelatedInfoAttribute(){
        $username = $this->user()->get(['username']);
        $avatar = $this->user()->get(['avatar']);
        $comment_id = $this->attributes['id'];
        $cache_nice_comment = Redis::hget('warshipcommunity:comment:nicecount',$comment_id);
        if($cache_nice_comment == null){
            $cache_nice_comment = $this->attributes['nice_comment'];
            if($cache_nice_comment == null){
                $cache_nice_comment = 0;
            }
        }
        $isNice = false;
        // 通过 web 访问
        /*if(Auth::check()){
            $existsInRedisSet = Redis::command('SISMEMBER', ['warshipcommunity:comment:nicelimit:' . $comment_id, Auth::user()->id]);
            if ($existsInRedisSet) {
                $isNice = true;
            }
        }*/
        // 通过 api 访问
        if(Auth::guard('api')->user()){
            $existsInRedisSet = Redis::command('SISMEMBER', ['warshipcommunity:comment:nicelimit:' . $comment_id, Auth::guard('api')->user()->id]);
            if ($existsInRedisSet) {
                $isNice = true;
            }
        }
        $data = [
            'username' => $username[0]->username,
            'avatar' => $avatar[0]->avatar,
            'cache_nice_comment' => $cache_nice_comment,
            'isNice' => $isNice
        ];
        return $data;
    }

    /**
     * 筛选在黑名单内的回复
     * @param $query
     */
    public function scopeBlacklist($query){
        $query->where('blacklist','!=',true);
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

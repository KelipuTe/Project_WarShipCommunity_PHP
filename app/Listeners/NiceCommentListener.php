<?php

namespace App\Listeners;

use App\Comment;
use App\Events\NiceComment;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;

/**
 * 评论被点赞事件监听器
 * Class NiceCommentListener
 * @package App\Listeners
 */
class NiceCommentListener
{
    /**
     * 点赞数量增加多少次后，刷新数据库
     */
    const refreshLimit = 30;

    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NiceComment  $event
     * @return void
     */
    public function handle(NiceComment $event)
    {
        $comment = $event->comment;
        $user_id = $event->user_id;
        if($this->userNiceComment($comment->id,$user_id)){
            // 一个 user 只能点赞一条回复一次
            $this->updateCacheNiceCommentCount($comment->id);
        }
    }

    public function userNiceComment($comment_id,$user_id){
        $userNiceCommentKey = 'warshipcommunity:comment:nicelimit:'.$comment_id;
        // Redis 命令 SISMEMBER 检查 Set 集合中有没有该键，Set 集合类型中值都是唯一
        $existsInRedisSet = Redis::command('SISMEMBER', [$userNiceCommentKey, $user_id]);
        // 如果集合中不存在这个键，那么新建一个键
        if(!$existsInRedisSet){
            // SADD，集合类型指令，向 $userNiceCommentKey 键中加一个值 $user_id
            Redis::command('SADD', [$userNiceCommentKey, $user_id]);
            return true;
        }
        return false;
    }

    public function updateCacheNiceCommentCount($comment_id){
        $commentNiceCountKey = 'warshipcommunity:comment:nicecount';
        // 这里以 redis 哈希类型存储键，就和数组类似，$discussionNiceCountKey 就类似数组名
        if(Redis::command('HEXISTS', [$commentNiceCountKey, $comment_id])){
            // 哈希类型指令 HINCRBY，就是给 $discussionNiceCountKey[$discussion_id] 加上一个值，这里就是 1
            $nice_comment = Redis::command('HINCRBY', [$commentNiceCountKey, $comment_id, 1]);
            // redis 中这个存储浏览量的值变化量达到 30 后，就去刷新一次 mysql 数据库
            if( ($nice_comment % self::refreshLimit) == 0 ){
                $this->updateModelNiceCommentCount($comment_id, $nice_comment);
            }
        } else {
            // 哈希类型指令 HSET，和数组类似,就像 $commentNiceCountKey[$discussion_id] = 1;
            Redis::command('HSET', [$commentNiceCountKey, $comment_id, '1']);
        }
    }

    public function updateModelNiceCommentCount($comment_id,$nice_comment){
        // 当访问量变化量达到 30 时,在进行一次 mysql 更新
        $comment = Comment::find($comment_id);
        $comment->nice_comment = $nice_comment;
        $comment->save();
    }
}

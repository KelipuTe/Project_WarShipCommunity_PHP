<?php

namespace App\Listeners;


use App\Events\NiceDiscussion;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;

class NiceDiscussionListener
{
    /**
     * 讨论浏览数量增加多少次后，刷新数据库
     */
    const refreshLimit = 10;

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
     * @param  NiceDiscussion  $event
     * @return void
     */
    public function handle(NiceDiscussion $event)
    {
        $discussion = $event->discussion;
        $user_id = $event->user_id;
        if($this->userNiceDiscussion($discussion->id,$user_id)){
            // 一个 user 只能推荐一篇讨论一次
            $this->updateCacheNiceCount($discussion->id);
        }
    }

    public function userNiceDiscussion($discussion_id,$user_id){
        $userNiceDiscussionKey = 'warshipcommunity:discussion:nicelimit:'.$discussion_id;
        // Redis 命令 SISMEMBER 检查 Set 集合中有没有该键，Set 集合类型中值都是唯一
        $existsInRedisSet = Redis::command('SISMEMBER', [$userNiceDiscussionKey, $user_id]);
        // 如果集合中不存在这个键，那么新建一个键
        if(!$existsInRedisSet){
            // SADD，集合类型指令，向 $userNiceDiscussionKey 键中加一个值 $user_id
            Redis::command('SADD', [$userNiceDiscussionKey, $user_id]);
            return true;
        }
        return false;
    }

    public function updateCacheNiceCount($discussion_id){
        $discussionNiceCountKey = 'warshipcommunity:discussion:nicecount';
        // 这里以 redis 哈希类型存储键，就和数组类似，$discussionNiceCountKey 就类似数组名
        if(Redis::command('HEXISTS', [$discussionNiceCountKey, $discussion_id])){
            // 哈希类型指令 HINCRBY，就是给 $discussionNiceCountKey[$discussion_id] 加上一个值，这里就是 1
            $hod_discussion = Redis::command('HINCRBY', [$discussionNiceCountKey, $discussion_id, 1]);
            // redis 中这个存储浏览量的值变化量达到 30 后，就去刷新一次 mysql 数据库
            if( ($hod_discussion % self::refreshLimit) == 0 ){
                $this->updateModelNiceCount($discussion_id, $hod_discussion);
            }
        } else {
            // 哈希类型指令 HSET，和数组类似,就像 $discussionNiceCountKey[$discussion_id] = 1;
            Redis::command('HSET', [$discussionNiceCountKey, $discussion_id, '1']);
        }
    }

    public function updateModelNiceCount($discussion_id,$nice_discussion){
        // 当访问量变化量达到 50 时,在进行一次 mysql 更新
        $discussion = Discussion::find($discussion_id);
        $discussion->nice_discussion = $nice_discussion;
        $discussion->save();
    }
}

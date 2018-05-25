<?php

namespace App\Listeners;

use App\Comment;
use App\Discussion;
use App\Events\NiceEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;

class NiceEventListener
{

    const refreshLimit = 30; // 讨论浏览数量增加多少次后，刷新数据库
    protected $type; // 被点赞目标的类型
    protected $target; // 被点赞目标

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
     * @param  NiceEvent  $event
     * @return void
     */
    public function handle(NiceEvent $event)
    {
        $this->type = $event->type; // 确定本次点赞事件的对象类型
        $this->setTarget($event->target_id); // 确定本次点赞事件的对象
        if($this->userNiceTarget($event->user_id)){
            // 一个 user 对一个对象只能点赞一次
            $this->updateCacheNiceCount($this->target->id);
        }
    }

    public function setTarget($id){
        switch($this->type){
            case 'discussion':
                $this->target = Discussion::find($id);break;
            case 'comment':
                $this->target = Comment::find($id);break;
        }
    }

    public function userNiceTarget($user_id){
        $userNiceKey = 'warshipcommunity:'.$this->type.':nicelimit:'.$this->target->id;
        // Redis 命令 SISMEMBER 检查 Set 集合中有没有该键，Set 集合类型中值都是唯一
        $existsInRedisSet = Redis::command('SISMEMBER', [$userNiceKey, $user_id]);
        // 如果集合中不存在这个键，那么新建一个键
        if(!$existsInRedisSet){
            // SADD，集合类型指令，向 $userNiceKey 键中加一个值 $user_id
            Redis::command('SADD', [$userNiceKey, $user_id]);
            return true;
        }
        return false;
    }

    public function updateCacheNiceCount($id){
        $niceCountKey = 'warshipcommunity:'.$this->type.':nicecount';
        // 这里以 redis 哈希类型存储键，就和数组类似，$discussionNiceCountKey 就类似数组名
        if(Redis::command('HEXISTS', [$niceCountKey, $id])){
            // 哈希类型指令 HINCRBY，就是给 $discussionNiceCountKey[$discussion_id] 加上一个值，这里就是 1
            $niceCount = Redis::command('HINCRBY', [$niceCountKey, $id, 1]);
            // redis 中这个存储浏览量的值变化量达到 30 后，就去刷新一次 mysql 数据库
            if( ($niceCount % self::refreshLimit) == 0 ){
                $this->updateModelNiceCount($niceCount);
            }
        } else {
            // 哈希类型指令 HSET，和数组类似,就像 $niceCountKey[$id] = 1;
            Redis::command('HSET', [$niceCountKey, $id, '1']);
        }
    }

    public function updateModelNiceCount($niceCount){
        // 当访问量变化量达到 30 时,在进行一次 mysql 更新
        $this->target->nice_discussion = $niceCount;
        $this->target->save();
    }
}

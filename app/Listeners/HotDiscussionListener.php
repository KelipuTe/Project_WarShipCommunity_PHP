<?php

namespace App\Listeners;

use App\Discussion;
use App\Events\HotDiscussion;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;

/**
 * 讨论被访问事件监听器
 * Class HotDiscussionListener
 * @package App\Listeners
 */
class HotDiscussionListener
{
    const refreshLimit = 30; // 讨论浏览数量增加多少次后，刷新数据库
    const ipExpireSecond = 3600; // 同一个 ip 有效访问的时间间隔

    /**
     * Create the event listener.
     */
    public function __construct(){
        //
    }

    /**
     * Handle the event.
     *
     * @param  HotDiscussion  $event
     * @return void
     */
    public function handle(HotDiscussion $event)
    {
        $discussion = $event->discussion;
        $ip = $event->ip;
        // 判断下 ipExpireSecond 时间内，同一 ip 多次访问
        if($this->ipViewLimit($discussion->id,$ip)){
            // 一个 ip 在 3600 秒时间内访问第一次时，刷新下该篇导论的浏览量
            $this->updateCacheViewCount($discussion->id);
        }
    }

    /**
     * 防止同一 ip 一段时间内的无效浏览次数
     * @param $id
     * @param $ip
     * @return bool
     */
    public function ipViewLimit($id,$ip){
        $ipDiscussionViewKey = 'warshipcommunity:discussion:viewlimit:'.$id;
        // Redis 命令 SISMEMBER 检查 Set 集合中有没有该键，Set 集合类型中值都是唯一
        $existsInRedisSet = Redis::command('SISMEMBER', [$ipDiscussionViewKey, $ip]);
        if(!$existsInRedisSet){
            // 如果集合中不存在这个键，使用 SADD 指令，向 $ipDiscussionViewKey 键中加一个值 ip
            Redis::command('SADD', [$ipDiscussionViewKey, $ip]);
            // 并给该键设置生命时间，这里设置 3600 秒，3600 秒后同一 ip 访问就当做是新的浏览量了
            Redis::command('EXPIRE', [$ipDiscussionViewKey, self::ipExpireSecond]);
            return true;
        }
        return false;
    }

    /**
     * 更新缓存中浏览次数
     * @param $id
     */
    public function updateCacheViewCount($id)
    {
        $discussionViewCountKey = 'warshipcommunity:discussion:viewcount';
        // 这里以 redis 哈希类型存储键，就和数组类似，$discussionViewCountKey 就类似数组名
        if(Redis::command('HEXISTS', [$discussionViewCountKey, $id])){
            // 哈希类型指令 HINCRBY，就是给 $discussionViewCountKey[$discussion_id] 加上一个值，这里一次访问就是 1
            $hod_discussion = Redis::command('HINCRBY', [$discussionViewCountKey, $id, 1]);
            // redis 中这个存储浏览量的值变化量达到 30 后，就去刷新一次 mysql 数据库
            if( ($hod_discussion % self::refreshLimit) == 0 ){
                $this->updateModelViewCount($id, $hod_discussion);
            }
        } else {
            // 哈希类型指令 HSET，和数组类似,就像 $discussionViewCountKey[$discussion_id] = 1;
            Redis::command('HSET', [$discussionViewCountKey, $id, '1']);
        }
    }

    /**
     * 达到要求更新数据库的浏览量
     * @param $id
     * @param $hod_discussion
     */
    public function updateModelViewCount($id, $hod_discussion)
    {
        $discussion = Discussion::find($id);
        $discussion->hot_discussion = $hod_discussion;
        $discussion->save();
    }
}

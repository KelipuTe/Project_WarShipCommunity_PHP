<?php

namespace App\Listeners;

use App\Events\PublicChatUserSignIn;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;

/**
 * 用户进入聊天室事件监听器
 * Class PublicChatUserSignInListener
 * @package App\Listeners
 */
class PublicChatUserSignInListener
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * 监听器事件处理方法
     * Handle the event.
     *
     * @param  PublicChatUserSignIn  $event
     * @return void
     */
    public function handle(PublicChatUserSignIn $event)
    {
        $key = "publicChatSignIn"; // Redis 数据库 键名
        if($event->type == 1) {
            /*
             * type = 1
             * 表示有用户进入公共聊天室
             */
            Redis::sadd($key, $event->username); // sadd() 函数用于Set 集合添加元素
            $data = [
                'event' => 'publicChatUserSignIn',
                'data' => Redis::smembers($key) // smembers() 函数用于返回 Set 集合元素
            ];
            Redis::publish('public-channel-user', json_encode($data));
        }else if ($event->type == -1){
            /*
             * type = -1
             * 表示有用户退出公共聊天室
             */
            if(Redis::sismember($key,$event->username)){
                Redis::srem($key, $event->username); // srem() 函数用于Set 集合删除元素
                $data = [
                    'event' => 'publicChatUserSignIn',
                    'data' => Redis::smembers($key)
                ];
                Redis::publish('public-channel-user', json_encode($data)); // 将数据发送至 Redis 数据库 public-channel-user 频道
            }
        }
    }
}

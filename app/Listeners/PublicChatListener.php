<?php

namespace App\Listeners;

use App\Events\PublicChat;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;

/**
 * 聊天事件监听器
 * Class PublicChatListener
 * @package App\Listeners
 */
class PublicChatListener
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
     * @param  PublicChat  $event
     * @return void
     */
    public function handle(PublicChat $event)
    {
        $data = [
            'event' => 'publicChat',
            'data' => [
                'username' => $event->chatMessage->getUsername(),
                'chatMessage' => $event->chatMessage->getChatMessage(),
                'time' => $event->chatMessage->getTime()->toDateTimeString()
            ]
        ];
        Redis::publish('public-channel',json_encode($data)); // 将数据发送至 Redis 数据库 public-channel 频道
    }
}

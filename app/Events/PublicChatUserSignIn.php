<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * 用户进入聊天室事件触发器
 * Class PublicChatUserSignIn
 * @package App\Events
 */
class PublicChatUserSignIn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * 用户登录状态
     * 登入：1，登出：-1
     * @var
     */
    public $type;

    /**
     * 用户名
     * @var
     */
    public $username;

    /**
     * 构造函数
     * Create a new event instance.
     *
     * @param $type
     * @param $username
     */
    public function __construct($type,$username)
    {
        $this->type = $type;
        $this->username = $username;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}

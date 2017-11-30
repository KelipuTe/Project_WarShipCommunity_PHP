<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PublicChatUserSignIn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * 用户登录状态，登入：1，登出：-1
     * @var
     */
    public $type;

    /**
     * 用户名
     * @var
     */
    public $username;

    /**
     * Create a new event instance.
     *
     * @return void
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

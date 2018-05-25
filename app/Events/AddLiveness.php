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
 * Class AddLiveness [活跃值事件]
 * @package App\Events
 */
class AddLiveness
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id; // 账户所属用户 id
    public $type; // 活跃值增加类型

    /**
     * Create a new event instance.
     *
     * @param $user_id
     * @param $type
     */
    public function __construct($user_id,$type)
    {
        $this->user_id = $user_id;
        $this->type = $type;
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

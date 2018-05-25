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
 * Class NiceEvent [点赞事件]
 * @package App\Events
 */
class NiceEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id; // 点赞用户 id
    public $type; // 被点赞目标的类型，如 'discussion','comment'
    public $target_id; // 被点赞目标的 id

    /**
     * Create a new event instance.
     *
     * @param $user_id
     * @param $type
     * @param $target_id
     */
    public function __construct($user_id,$type,$target_id)
    {
        $this->user_id = $user_id;
        $this->type = $type;
        $this->target_id = $target_id;
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

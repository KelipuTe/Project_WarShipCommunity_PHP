<?php

namespace App\Events;

use App\Discussion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * 讨论被点赞事件触发器
 * Class NiceDiscussion
 * @package App\Events
 */
class NiceDiscussion
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * 触发点赞事件的讨论
     * @var Discussion
     */
    public $discussion;

    /**
     * 点赞的用户 id
     * @var
     */
    public $user_id;

    /**
     * Create a new event instance.
     *
     * @param Discussion $discussion
     * @param $user_id
     */
    public function __construct(Discussion $discussion,$user_id)
    {
        $this->discussion = $discussion;
        $this->user_id = $user_id;
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

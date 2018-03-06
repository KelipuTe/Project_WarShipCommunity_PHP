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
 * 讨论被访问事件触发器
 * Class HotDiscussion
 * @package App\Events
 */
class HotDiscussion
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * 触发访问事件的讨论
     * @var Discussion
     */
    public $discussion;

    /**
     * 访问者的 ip 地址
     * @var
     */
    public $ip;

    /**
     * Create a new event instance.
     *
     * @param Discussion $discussion
     * @param $ip
     */
    public function __construct(Discussion $discussion,$ip)
    {
        $this->discussion = $discussion;
        $this->ip = $ip;
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

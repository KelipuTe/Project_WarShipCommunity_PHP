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

class DiscussionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $discussion;

    /**
     * Create a new event instance.
     *
     * @param $type
     * @param Discussion $discussion
     */
    public function __construct($type,Discussion $discussion)
    {
        $this->type = $type;
        $this->discussion = $discussion;
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

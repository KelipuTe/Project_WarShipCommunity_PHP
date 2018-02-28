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

class NiceDiscussion
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $discussion;
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

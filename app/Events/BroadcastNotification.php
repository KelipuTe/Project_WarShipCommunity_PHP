<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class BroadcastNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $user_id;
    protected $type;

    public $notification;

    /**
     * Create a new event instance.
     *
     * @param $notification
     * @param int $user_id
     */
    public function __construct($notification,$user_id = 0){
        $this->user_id = $user_id;
        $this->type = 'private';
        if($this->user_id == 0){
            $this->type = 'public';
        }
        $this->notification = $notification->data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if($this->type == 'private') {
            return new PrivateChannel('broadcast-notification-'.$this->user_id);
        }
        return new Channel('broadcast-notification');
    }
}

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

    protected $user_id; // 被通知的对象
    protected $broadcastType; // 广播的类型，如'public','private'

    public $notification; // 被广播的消息
    public $notificationType;

    /**
     * Create a new event instance.
     *
     * @param $notification
     * @param int $user_id
     */
    public function __construct($notification,$user_id = 0){
        $this->user_id = $user_id;
        $this->broadcastType = 'private';
        if($this->user_id == 0){
            // 如果广播没有设置被通知的对象，即为全站广播
            $this->broadcastType = 'public';
        }
        $this->notification = $notification->data;
        $this->notificationType = $notification->type;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if($this->broadcastType == 'private') {
            // broadcast-notification-1' 用户私有频道
            return new PrivateChannel('broadcast-notification-'.$this->user_id);
        }
        return new Channel('broadcast-notification');
    }
}

<?php

namespace App\Events;

use App\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * 评论被点赞事件触发器
 * Class NiceComment
 * @package App\Events
 */
class NiceComment
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * 触发点赞事件的评论
     * @var Comment
     */
    public $comment;

    /**
     * 点赞的用户 id
     * @var
     */
    public $user_id;

    /**
     * Create a new event instance.
     *
     * @param Comment $comment
     * @param $user_id
     */
    public function __construct(Comment $comment,$user_id)
    {
        $this->comment = $comment;
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

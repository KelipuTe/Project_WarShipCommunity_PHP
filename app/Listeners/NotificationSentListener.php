<?php

namespace App\Listeners;

use App\Events\BroadcastNotification;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

/**
 * Class NotificationSentListener [监听 event(new notification)]
 * @package App\Listeners
 */
class NotificationSentListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NotificationSent  $event
     * @return void
     */
    public function handle(NotificationSent $event)
    {
        $notification = DB::table('notifications')
            ->where('id','=',$event->notification->id)->first();
        // $notification 为消息通知实体 ，$notifiable_id 为用户 id
        event(new BroadcastNotification($notification,$notification->notifiable_id));
    }
}

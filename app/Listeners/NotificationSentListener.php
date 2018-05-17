<?php

namespace App\Listeners;

use App\Events\BroadcastNotification;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

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
        $notification = DB::table('notifications')->where('id','=',$event->notification->id)->first();
        event(new BroadcastNotification($notification,$notification->notifiable_id));
    }
}

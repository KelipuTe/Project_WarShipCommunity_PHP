<?php

namespace App\Listeners;

use App\Events\DiscussionEvent;
use App\Notifications\DiscussionCreateNotification;
use App\Notifications\DiscussionNotification;
use App\Notifications\DiscussionUpdateNotification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DiscussionEventListener
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
     * @param  DiscussionEvent  $event
     * @return void
     */
    public function handle(DiscussionEvent $event)
    {
        $discussion = $event->discussion;
        switch ($event->type){
            case 'create':
                $user = $discussion->user;
                $followers = $user->userUserFollower()->pluck('follower_id')->toArray();
                foreach ($followers as $follower){
                    $user = User::find($follower);
                    $user->notify(new DiscussionNotification('create',$discussion->id));
                }
            case 'update':
                $followers = $discussion->userDiscussion()->pluck('user_id')->toArray();
                foreach ($followers as $follower){
                    $user = User::find($follower);
                    $user->notify(new DiscussionNotification('update',$discussion->id));
                }
        }
    }
}

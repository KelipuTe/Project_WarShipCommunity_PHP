<?php

namespace App\Listeners;

use App\Events\PublicChat;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;

class PublicChatListener
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
     * @param  PublicChat  $event
     * @return void
     */
    public function handle(PublicChat $event)
    {
        $data = [
            'event' => 'publicChat',
            'data' => [
                'username' => $event->chatMessage->getUsername(),
                'chatMessage' => $event->chatMessage->getChatMessage(),
                'time' => $event->chatMessage->getTime()->toDateTimeString()
            ]
        ];
        Redis::publish('public-channel',json_encode($data));
    }
}

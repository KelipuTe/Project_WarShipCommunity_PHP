<?php

namespace App\Listeners;

use App\Events\PublicChatUserSignIn;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;

class PublicChatUserSignInListener
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
     * @param  PublicChatUserSignIn  $event
     * @return void
     */
    public function handle(PublicChatUserSignIn $event)
    {
        $key = "publicChatSignIn";
        if($event->type == 1) {
            //type=1表示有用户进入公共聊天室
            Redis::sadd($key, $event->username);
            $data = [
                'event' => 'publicChatUserSignIn',
                'data' => Redis::smembers($key)
            ];
            Redis::publish('public-channel-user', json_encode($data));
        }else if ($event->type == -1){
            //type=-1表示有用户退出公共聊天室
            if(Redis::sismember($key,$event->username)){
                Redis::srem($key, $event->username);
                $data = [
                    'event' => 'publicChatUserSignIn',
                    'data' => Redis::smembers($key)
                ];
                Redis::publish('public-channel-user', json_encode($data));
            }
        }
    }
}

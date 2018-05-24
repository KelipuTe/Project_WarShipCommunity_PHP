<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

//Broadcast::channel('broadcast-notification', function ($user) {
//    return $user->id == 1;
//});

// 消息通知身份验证
Broadcast::channel('broadcast-notification-{user_id}', function ($user,$user_id) {
    return $user->id == $user_id;
});

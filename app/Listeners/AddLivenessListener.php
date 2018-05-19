<?php

namespace App\Listeners;

use App\Account;
use App\Events\AddLiveness;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddLivenessListener
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
     * @param  AddLiveness  $event
     * @return void
     */
    public function handle(AddLiveness $event)
    {
        $account = Account::find($event->user_id);
        if($event->type == 'activitySign'){
            $power = 1; // 签到时的积分增加倍率
            $account->addLiveness($event->type,$power);
        } else {
            $account->addLiveness($event->type);
        }
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        /*'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],*/
        'Illuminate\Notifications\Events\NotificationSent' => [
            'App\Listeners\NotificationSentListener',
        ],
        'App\Events\PublicChat' => [
            'App\Listeners\PublicChatListener',
        ],
        'App\Events\PublicChatUserSignIn' => [
            'App\Listeners\PublicChatUserSignInListener',
        ],
        'App\Events\HotDiscussion' => [
            'App\Listeners\HotDiscussionListener',
        ],
        'App\Events\AddLiveness' => [
            'App\Listeners\AddLivenessListener',
        ],
        'App\Events\NiceEvent' => [
            'App\Listeners\NiceEventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}

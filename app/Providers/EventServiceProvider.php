<?php

namespace App\Providers;

use App\Events\ThreadReceivedNewReply;
use App\Listeners\NotifyMentionedUsers;
use App\Listeners\NotifySubscribers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ThreadReceivedNewReply::class => [
            NotifyMentionedUsers::class,
            NotifySubscribers::class
        ],

        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

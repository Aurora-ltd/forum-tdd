<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouAreMentioned;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentionedUsers
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
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        User::whereIn('name', $event->reply->mentionedUsers())
            ->get()
            ->each(function ($user) use ($event) {
                $user->notify(new YouAreMentioned($event->reply));
            });
        // dd($users);
        // collect($event->reply->mentionedUsers())
        //     ->map(function ($name) {
        //         return User::where('name', $name)->first();
        //     })
        //     ->filter()
        //     ->each(function ($user) use ($event) {
        //         $user->notify(new YouAreMentioned($event->reply));
        //     });
    }
}

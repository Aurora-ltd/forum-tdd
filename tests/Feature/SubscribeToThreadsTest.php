<?php

namespace Tests\Feature;

use App\Models\ThreadSubscription;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscribeToThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_subscribe_to_threads()
    {
        $this->signIn();

        // Given we have a thread
        $thread = create('App\Models\Thread');

        // And the user subscribes to the thread
        $this->post($thread->path() . '/subscriptions');

        // Then, each time a new reply is left..
        // $thread->addReply([
        //     'user_id' => auth()->id(),
        //     'body' => 'What is on your mind'
        // ]);

        // dd($thread->subscriptions->count());

        // A notification should be prepared for the user.
        $this->assertCount(0, $thread->subscriptions);
    }
}
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $this->signIn();

        // Given we have a thread
        $thread = create('App\Models\Thread');

        $thread->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        // Then, each time a new reply is left..
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'What is on your mind'
        ]);

        // A notification should be prepared for the user.
        $this->assertCount(0, auth()->user()->fresh()->notifications);

        // Then, each time a new reply is left..
        $thread->addReply([
            'user_id' => create('App\Models\User')->id,
            'body' => 'What is on your mind'
        ]);

        // A notification should be prepared for the user.
        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    public function test_a_user_can_fetch_their_unread_notifications()
    {
        $this->signIn();

        // Given we have a thread
        $thread = create('App\Models\Thread');

        $thread->subscribe();

        $thread->addReply([
            'user_id' => create('App\Models\User')->id,
            'body' => 'What is on your mind'
        ]);

        $user = auth()->user();

        $response = $this->getJson("/profiles/{$user->name}/notifications")->json();

        $this->assertCount(1, $response);
    }

    public function test_a_user_can_mark_a_notification_as_read()
    {
        $this->signIn();

        // Given we have a thread
        $thread = create('App\Models\Thread');

        $thread->subscribe();

        $thread->addReply([
            'user_id' => create('App\Models\User')->id,
            'body' => 'What is on your mind'
        ]);

        $user = auth()->user();

        $this->assertCount(1, $user->unreadNotifications);

        $notificationId = $user->unreadNotifications->first()->id;
        // dd("/profiles/" . $user->name. "/notifications/{$notificationId}");

        $this->delete("/profiles/" . $user->name. "/notifications/{$notificationId}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}

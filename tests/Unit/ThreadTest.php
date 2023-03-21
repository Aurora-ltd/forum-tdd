<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp(): void
    {
        parent::setUp();
        $this->thread = Thread::factory()->create();
    }

    /** @test */
    public function a_thread_can_make_a_string_path()
    {
        $thread = make('App\Models\Thread');

        $this->assertEquals('/threads/' . $thread->channel->slug. '/' . $thread->id, $thread->path());
    }

    /** @test */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    public function a_thread_has_creator()
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    public function test_a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn();

        $this->thread->subscribe();

        $this->thread->addReply([
            'body' => 'test notices',
            'user_id' => 1
        ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Models\Thread');

        $this->assertInstanceOf('App\Models\Channel', $thread->channel);
    }

    public function test_a_thread_can_be_subscribed_to()
    {
        // Given we hava thread
        $thread = create('App\Models\Thread');

        // And an authenticate user
        // $this->signIn();

        // When the user subscribe to the thread
        $thread->subscribe($userId = 1);

        // Then we should be able to fetch all threads that the user has subscribed to.
        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', $userId)->count()
        );
    }

    public function test_a_thread_can_be_unsubscribed_from()
    {
        // Given we hava thread
        $thread = create('App\Models\Thread');

        // When the user subscribe to the thread
        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);

        $this->assertCount(0, $thread->subscriptions);
    }

    public function test_it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        // Given we hava thread
        $thread = create('App\Models\Thread');

        // And an authenticate user
        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    public function test_a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $thread = create('App\Models\Thread');

        tap(auth()->user(), function ($user) use ($thread) {
            $this->assertTrue($thread->hasUpdatesFor($user));

            $user->read($thread);

            $this->assertFalse($thread->hasUpdatesFor($user));
        });
    }
}

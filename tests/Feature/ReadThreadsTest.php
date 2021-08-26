<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;
    protected $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $this->thread =  Thread::factory()->create();
    }
    /** @test */
    public function a_user_can_view_all_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_a_single_thread()
    {
        $this->get($this->thread->path())
                ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = Reply::factory()
                    ->create(['thread_id' => $this->thread->id]);
        // when we visite the thread page
        $this->get($this->thread->path())
                ->assertSee($reply->body);
        // Then we should see the replies
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Models\Channel');
        $threadInChannel = create('App\Models\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Models\Thread');

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\Models\User', ['name' => 'jamiul']));

        $threadByJamiul = create('App\Models\Thread', ['user_id' => auth()->id()]);
        $threadNotByJamiul = create('App\Models\Thread');

        $this->get('/threads?by=jamiul')
            ->assertSee($threadByJamiul->title)
            ->assertDontSee($threadNotByJamiul->title);
    }
}

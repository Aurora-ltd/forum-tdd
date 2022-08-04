<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use RefreshDatabase;
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

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        // Given we have three threads
        // With 2 replies, 3 replies, 0 replies
        $threadWithTwoReplies = create('App\Models\Thread');
        create('App\Models\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithThreeReplies = create('App\Models\Thread');
        create('App\Models\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithNoReplies = $this->thread;


        // When I filter all threads by popularity
        $response = $this->getJson('threads?popular=1')->json();

        // Then they should be returned from most replies to least
        $this->assertEquals([3, 2, 0, 0, 0, 0, 0, 0], array_column($response, 'replies_count'));
    }

    public function test_a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = create(Thread::class);

        create(Reply::class, ['thread_id' => $thread->id], 2);

        $response = $this->getJson($thread->path() . '/replies')->json();

        // dd($response);
        $this->assertCount(1, $response['data']);
        $this->assertEquals(2, $response['total']);

    }
}

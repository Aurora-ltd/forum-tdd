<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\AuthenticationException;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        $this->withoutExceptionHandling();
        //     ->post('/threads/my-channel/1/replies', [])
        //     ->assertRedirect('/login');

        $this->expectException(AuthenticationException::class);
        $thread = Thread::factory()->create();

        $reply = Reply::factory()->create();
        $this->post('/threads/my-channel/1/replies', []);
    }

    /** @test */
    public function an_authenticate_user_may_participate_in_forum_threads()
    {
        $this->signIn();

        $thread = create(Thread::class);
        $reply = make(Reply::class);

        $this->post($thread->path().'/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $thread = create(Thread::class);
        $reply = make(Reply::class, ['body' => null]);

        $this->post($thread->path().'/replies', $reply->toArray())
                    ->assertSessionHasErrors('body');
    }

    public function test_unautorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Models\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    public function test_authorized_users_can_delete_replies()
    {
        $this->signIn();

        $reply = create('App\Models\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    public function test_authorized_users_can_update_replies()
    {
        // $this->withoutExceptionHandling();

        $this->signIn();

        $reply = create('App\Models\Reply', ['user_id' => auth()->id()]);

        $updateReply = 'update reply';

        $this->patch("/replies/{$reply->id}", ['body' => $updateReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updateReply]);
    }

    public function test_unautorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Models\Reply');

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    public function test_replies_that_contain_spam_may_not_be_created()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $thread = create(Thread::class);
        $reply = make(Reply::class,[
            'body' => 'Daraz Customer Support'
        ]);

        $this->json('post', $thread->path().'/replies', $reply->toArray())
            ->assertStatus(422);
    }

    public function test_users_may_only_reply_a_maximum_of_once_per_minute()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $thread = create(Thread::class);
        $reply = make(Reply::class,[
            'body' => 'My awesome reply.'
        ]);

        $this->post($thread->path().'/replies', $reply->toArray())
            ->assertStatus(201);

        $this->post($thread->path().'/replies', $reply->toArray())
            ->assertStatus(422);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Auth\AuthenticationException;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

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

        $thread = Thread::factory()->create();
        $reply = Reply::factory()->make();

        $this->post($thread->path().'/replies', $reply->toArray());

        // Then their reply should be visible on the page
        $this->get($thread->path())
                    ->assertSee($reply->body);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
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

    public function test_authorized_users_cannot_delete_replies()
    {
        $this->signIn();

        $reply = create('App\Models\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}");
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }
}

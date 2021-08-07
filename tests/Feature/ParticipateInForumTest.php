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
        $this->withoutExceptionHandling();
        // Given we have an authentication user
        $user = User::factory()->create();
        $this->be($user);

        // And an existing thread\
        $thread = Thread::factory()->create();

        // dd($thread->path().'/replies');
        // When the user adds a reply to the thread
        $reply = Reply::factory()->make();
        $this->post($thread->path().'/replies', $reply->toArray());

        // Then their reply should be visible on the page
        $this->get($thread->path())
            ->assertSee($reply->body);
    }
}

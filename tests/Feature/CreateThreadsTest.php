<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Thread;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Auth\AuthenticationException;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guest_may_not_create_threads()
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $thread = Thread::factory()->make();
        $this->post('/threads', $thread->toArray());
    }
    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        // Given we have a signed in user
        $this->actingAs(User::factory()->create());

        // When we hit the endpoint to create a new thread
        $thread = Thread::factory()->make();
        $this->post('/threads', $thread->toArray());

        // Then when we visit the thread page
        // $this->get($thread->path());

        // We should see the new thread
        $this->get($thread->path())
                ->assertSee($thread->title)
                ->assertSee($thread->body);
    }
}

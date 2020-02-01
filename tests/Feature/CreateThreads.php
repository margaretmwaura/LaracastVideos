<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreads extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    /** @test */
    use DatabaseMigrations;
    function guests_may_not_create_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $thread=factory('App\Thread')->make();
        $this->post('/threads',$thread->toArray());
    }
    public function an_authenticated_user_can_create_new_forum_threads()
    {

        $this->actingAs(factory('App\User')->create());

        $thread=factory('App\Thread')->make();
        $this->post('/threads',$thread->toArray());
        $this->get($thread->path())
            ->assertSee($thread->title)
              ->assertSee($thread->body);

    }

}
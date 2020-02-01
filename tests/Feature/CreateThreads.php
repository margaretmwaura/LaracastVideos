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

    /** @test */
    function guests_may_not_create_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $thread=make('App\Thread');
        $this->post('/threads',$thread->toArray());
    }

    /** @test */
    function guests_can_not_see_the_create_thread_page()
    {
        $this->withExceptionHandling()
             ->get('/threads/create')
            ->assertRedirect('/login');
    }


    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {

        $this->signIn();

        $thread=make('App\Thread');
        $this->post('/threads',$thread->toArray());
        $this->get($thread->path())
            ->assertSee($thread->title)
              ->assertSee($thread->body);

    }

}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    /** @test */
    use DatabaseMigrations;

    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        $this->withExceptionHandling()
              ->post('/threads/some-channel/1/replies',[])
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forums()
    {
        $user=factory('App\User')->create();
        $this->be($user);
        $thread=factory('App\Thread')->create();
        $reply=factory('App\Reply')->make();
        $this->post($thread->path().'/replies',$reply->toArray());
        $this->get($thread->path())
            ->assertSee($reply->body);
    }
    function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();
        $thread=factory('App\Thread')->create();
        $reply=factory('App\Reply',['body'=>null])->make();
        $this->post($thread->path().'/replies',$reply->toArray())
              ->assertSessionHasErrors('body');
    }
}

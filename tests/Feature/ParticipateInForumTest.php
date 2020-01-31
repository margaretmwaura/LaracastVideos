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
    public function an_authenticated_user_may_participate_in_forums()
    {
        $user=factory('App\User')->create();
        $this->be($user);
        $thread=factory('App\Thread')->create();
        $reply=factory('App\Reply')->create();
        $this->post('/threads/'.$thread->id.'/replies',$reply->toArray());
//        $this->get($thread->path())
//            ->assertSee($reply->body);
    }
}

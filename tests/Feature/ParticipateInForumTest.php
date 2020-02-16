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
        $this->assertDatabaseHas('replies',['body'=>$reply->body]);
        $this->assertEquals(1,$thread->fresh()->replies_count);
    }

    /** @test */
    function a_reply_requires_a_body()
    {
        $this->withExceptionHandling();
        $this->withExceptionHandling()->signIn();
        $thread=factory('App\Thread')->create();
        $reply=factory('App\Reply',['body'=>null])->make();
        $this->json('post',$thread->path().'/replies',$reply->toArray())
            ->assertStatus(422);
    }

    /** @test */
    function unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();
        $reply=create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('/login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    function authorized_users_can_delete_replies()
    {
        $this->signIn();
        $reply=create('App\Reply',['user_id' => auth()->id()]);
        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies',['id' => $reply->id]);
        $this->assertEquals(0,$reply->thread->fresh()->replies_count);
    }

    /** @test */
    public function authorized_users_can_update_replies()
    {
        $this->signIn();
        $reply=create('App\Reply',['user_id' => auth()->id()]);
        $this->patch("/replies/{$reply->id}", ['body' => 'You have been changes fool']);

        $this->assertDatabaseHas('replies',['id' => $reply->id,'body' => 'You have been changes fool']);

    }

    /** @test */
    public function un_authorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();
        $reply=create('App\Reply');
        $this->patch("/replies/{$reply->id}")->assertRedirect('/login');

        $this->signIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function replies_that_contain_spam_may_not_be_created()
    {
        $this->withExceptionHandling();
        $user=factory('App\User')->create();
        $this->be($user);
        $thread=factory('App\Thread')->create();
        $reply=factory('App\Reply',[
            'body' => 'Yahoo Customer Support'
        ])->make();


        $this->json('post',$thread->path().'/replies',$reply->toArray())
            ->assertStatus(422);
    }

    /** @test */
    public function users_may_only_reply_a_maximum_of_once_per_minute()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply');

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(200);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(422);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
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

        $this->withExceptionHandling();

           $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();
        $thread=make('App\Thread');
       $response=$this->post('/threads',$thread->toArray());
        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
              ->assertSee($thread->body);

    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title'=>null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body'=>null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_channel_id()
    {
        factory('App\Channel',2)->create();
        $this->publishThread(['channel_id'=>null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id'=>999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->signIn();
        $thread=create('App\Thread',['user_id' => auth()->id()]);
        $reply=create('App\Reply',['thread_id' => $thread->id]);
       $response=$this->json('DELETE',$thread->path());
       $response->assertStatus(204);
        $this->assertDatabaseMissing('threads',['id' => $thread->id]);
        $this->assertDatabaseMissing('replies',['id' => $reply->id]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type'=>get_class($thread)
            ]);
    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();
        $thread=create('App\Thread');
        $response=$this->delete($thread->path())
            ->assertRedirect('/login');

        $this->signIn();
        $this->delete($thread->path())->assertStatus(403);
    }
    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();
        $thread=make('App\Thread',$overrides);
       return $this->post('/threads',$thread->toArray());

    }
}

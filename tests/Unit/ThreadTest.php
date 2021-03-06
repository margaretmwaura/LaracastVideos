<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     *//** @test */

    use DatabaseMigrations;
    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread=factory('App\Thread')->create();
    }

    /** @test */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection',$this->thread->replies);
    }

    /** @test */
    public function a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\User',$this->thread->creator);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
       $this->thread->addReply([
           'body'=>'Foobar',
           'user_id'=>1
       ]);

       $this->assertCount(1,$this->thread->replies);
    }
    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
       $thread=create('App\Thread');
       $this->assertInstanceOf('App\Channel' , $thread->channel);
    }

    /** @test */
    function a_thread_can_make_a_string_path()
    {
        $thread=create('App\Thread');
        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->id}",$thread->path());
    }

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $thread = create('App\Thread');
//        $this->signIn();
        $thread->subscribe(1);
        $this->assertEquals(1,$thread->subscriptions()->where('user_id',1)->count());
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        $thread = create('App\Thread');
//        $this->signIn();
        $thread->subscribe(1);
        $thread->unsubscribe(1);
        $this->assertCount(0,$thread->subscriptions);
    }

    /** @test */
    public function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $thread = create('App\Thread');
        $this->signIn();
        $this->assertFalse($thread->isSubscribedTo);
        $thread->subscribe();
        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    public function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();
        $this->signIn();
//        $this->thread->subscribe();

        $this->thread->subscribe()->addReply([
            'body'=>'Foobar',
            'user_id'=>1
        ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test
     * @throws \Exception
     */
    public function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $this->assertTrue($thread->hasUpdatesFor(auth()->user()));

        auth()->user()->read($thread);


        $this->assertFalse($thread->hasUpdatesFor(auth()->user()));
    }
}

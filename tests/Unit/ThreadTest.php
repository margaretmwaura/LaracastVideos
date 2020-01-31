<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
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

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }

    public function a_thread_has_replies()
    {
        $thread=factory('App\Thread')->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection',$thread->replies);
    }

    /** @test */
    public function a_thread_has_a_creator()
    {
        $thread=factory('App\Thread')->create();
        $this->assertInstanceOf('App\User',$thread->creator);
    }

    public function a_thread_can_add_a_reply()
    {
       $this->thread->addReply([
           'body'=>'Foobar',
           'user_id'=>1
       ]);

    }
}

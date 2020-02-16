<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseMigrations;

    /** @test */
    public function it_has_an_owner()
    {
       $reply = factory('App\Reply')->create();
       $this->assertInstanceOf('App\User',$reply->owner);
    }

    /** @test */
    public function it_knows_if_it_was_just_published()
    {
        $reply = factory('App\Reply')->create();

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = create('App\Reply',[
            'body' => '@JaneDoe wants to talk to @JohnDoe'
        ]);

        $this->assertEquals(['JaneDoe','JohnDoe'] ,$reply->mentionedUsers() );
    }

    /** @test */
    public function it_wraps_mentioned_user_names_in_the_body_within_anchor_tags()
    {
        $reply = create('App\Reply',[
            'body' => '@JaneDoe'
        ]);

        $this->assertEquals('<a href="/profiles/JaneDoe">@JaneDoe</a>', $reply->body);
    }
}

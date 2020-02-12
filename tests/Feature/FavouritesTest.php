<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavouritesTest extends TestCase
{
     use DatabaseMigrations;

    /** @test */
    public function guests_cannot_favorite_anything()
    {
        $this->withExceptionHandling();
        $this->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

     /** @test */
    public function an_authenticated_user_can_favourite_any_reply()
    {
        $this->signIn();
        $reply=create('App\Reply');
        $this->post('replies/'.$reply->id.'/favorites');
        $this->assertCount(1,$reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_may_only_favorite_a_reply_once()
       {
           $this->signIn();
           $reply=create('App\Reply');

           try{
               $this->post('replies/'.$reply->id.'/favorites');
               $this->post('replies/'.$reply->id.'/favorites');
           }
           catch (\Exception $e)
           {
               $this->fail("Did not expect to insert same record twice");
           }


           $this->assertCount(1,$reply->favorites);
       }

    /** @test */
    public function an_authenticated_user_may_only_unFavorite_a_reply()
    {
        $this->signIn();
        $reply=create('App\Reply');

        $this->post('replies/'.$reply->id.'/favorites');

        $this->assertCount(1,$reply->favorites);

        $this->delete('replies/'.$reply->id.'/favorites');


        $this->assertCount(0,$reply->fresh()->favorites);
    }
}


<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $this->signIn();
        $thread = create('App\Thread')->subscribe();
        $this->assertCount(0,auth()->user()->notifications);
        $thread->addReply([
            'user_id'=>auth()->id(),
             'body' => 'Some reply here']);

        $this->assertCount(0,auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id'=>create('App\User')->id,
            'body' => 'Some reply here']);

        $this->assertCount(1,auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {
        $this->signIn();
        $thread = create('App\Thread')->subscribe();
        $thread->addReply([
            'user_id'=>create('App\User')->id,
            'body' => 'Some reply here']);

        $this->assertCount(1,auth()->user()->unreadNotifications);

        $notificationId =  auth()->user()->unreadNotifications->first()->id;
        $this->delete("/profiles/".auth()->user()->name."/notifications/{$notificationId}");
        $this->assertCount(0,auth()->user()->fresh()->unreadNotifications);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
        $this->signIn();
        $thread = create('App\Thread')->subscribe();
        $thread->addReply([
            'user_id'=>create('App\User')->id,
            'body' => 'Some reply here']);
       $response = $this->getJson("/profiles/".auth()->user()->name."/notifications")->json();
       $this->assertCount(1,$response);
    }
}

<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWereMentioned;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentionedUsers
{

    public function __construct()
    {
        //
    }

    public function handle(ThreadReceivedNewReply $event)
    {

        User::whereIn('name',$event->reply->mentionedUsers())->get()
            ->each(function ($user) use ($event){
                $user->notify(new YouWereMentioned($event->reply));
            });

    }
}

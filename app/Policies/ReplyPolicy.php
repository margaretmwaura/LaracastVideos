<?php

namespace App\Policies;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Reply $reply)
    {
        return $reply->user_id == $user->id;
    }

    public function create(User $user)
    {
       $last_reply = $user->fresh()->lastReply;

       if(! $last_reply) return true;

       return ! $last_reply->wasJustPublished();
    }
}

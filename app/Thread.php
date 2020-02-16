<?php

namespace App;

use App\Events\ThreadHasNewReply;
use App\Events\ThreadReceivedNewReply;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;
use function foo\func;

class Thread extends Model
{
    //
    use RecordsActivity;
    protected $guarded = [];
    protected $with = ['creator','channel'];
    protected $appends = ['isSubscribedTo'];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::deleting(function ($thread){
            $thread->replies->each->delete();
        });


    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }
    public function replies()
    {
        return $this->hasMany(Reply::class);
//            ->withCount('favorites')
//            ->with('owner');
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadHasNewReply($this,$reply));

        event(new ThreadReceivedNewReply($reply));
//        $this->notifySubscribers($reply);
        return $reply;
    }

    public function scopeFilter($query,$filters)
    {
        return $filters->apply($query);
    }

    public function getReplyCountAttribute()
    {
        return $this->replies()->count();
    }

    public function subscribe($userId = null)
    {
       $this->subscriptions()->create([
           'user_id' => $userId ?: auth()->id()
       ]);

       return $this;
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
               ->where('user_id',auth()->id())
               ->exists();
    }
    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id' , $userId ?: auth()->id())
            ->delete();
    }
    public function subscriptions()
    {
       return $this->hasMany(ThreadSubscription::class);
    }

    public function notifySubscribers($reply)
    {

        $this->subscriptions
            ->where('user_id','!=' , $reply->user_id)
            ->each
            ->notify($reply);
    }

    public function hasUpdatesFor($user = null)
    {
        $user = $user ?: auth()->user();
        $key = $user->visitedThreadCacheKey($this);
        try {
            return $this->updated_at > cache($key);
        } catch (\Exception $e) {
        }
    }
}

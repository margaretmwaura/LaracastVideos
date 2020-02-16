<?php

namespace App\Http\Controllers;

use App\Http\Forms\CreatePostForm;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Inspections\Spam;
use App\Thread;
use App\User;


class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth' , ['except' => 'index']);
    }

    public function index($channelId , Thread $thread)
    {
       return $thread->replies()->paginate(10);
    }
    public function store($channel_id,Thread $thread , CreatePostForm $form)
    {
       $reply =  $thread->addReply([
                'body'=> \request('body'),
                'user_id' => auth()->id()
        ]);
       preg_match_all('/\@([^\s\.]+)/',$reply->body,$matches);

      $names = $matches[1];

      foreach ($names as $name)
      {
          $user = User::whereName($name)->first();

          if($user)
          {
              $user->notify(new YouWereMentioned($reply));
          }
      }
       return $reply->load('owner');

    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update',$reply);
       $reply->delete();
       if(request()->expectsJson())
       {
           return response(['status'=>'Reply deleted']);
       }
       return back();
    }

    public function update(Reply $reply)
    {
        $this->authorize('update',$reply);

        try {

            $this->validate(request(), ['body' => 'required|spamFree']);
            $reply->update(['body' => request('body')]);

        }catch (\Exception $exception)
        {
            return response('Sorry your reply could not be saved at this time' , 422);
        }

    }



}

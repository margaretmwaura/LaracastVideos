<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Inspections\Spam;
use App\Thread;
use http\Env\Response;
use Illuminate\Http\Request;

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
    public function store($channel_id,Thread $thread , Spam $spam)
    {

        $this->validateReply();
       $reply=$thread->addReply([
            'body'=> \request('body'),
            'user_id' => auth()->id()
            ]);

        if(\request()->expectsJson()) {
              return $reply->load('owner');
          }
        return back();
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

    public function update(Reply $reply, Spam $spam)
    {
        $this->authorize('update',$reply);
        $this->validateReply();
        $reply->update(['body' => request('body')]);
    }


    protected function validateReply()
    {
        $this->validate(request(), ['body' => 'required']);

        resolve(Spam::class)->detect(request('body'));
    }
}

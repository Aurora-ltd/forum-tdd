<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;
use App\Inspections\Spam;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(5);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($channelId, Request $request, Thread $thread, Spam $spam)
    {
        $this->validateReply();

        $reply = $thread->addReply([
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

        return back()->with('flash', 'Your reply has been left.');
    }

    /**
     * update the reply.
     *
     * @param  mixed $request
     * @param  mixed $reply
     * @param  mixed $spam
     * @return void
     */
    public function update(Request $request, Reply $reply, Spam $spam)
    {
        $this->authorize('update', $reply);

        $this->validate(request(), ['body' => 'required']);
        $spam->detect($request->body);

        $reply->update([ 'body' => $request->body]);
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => "Reply delete"]);
        }

        return back();
    }

    /**
     * Validate the incoming reply.
     *
     * @return void
     */
    public function validateReply()
    {
        $this->validate(request(),['body' => 'required']);

        resolve(Spam::class)->detect(request('body'));
    }
}

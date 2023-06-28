<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Reply;
use App\Models\Thread;
use App\Rules\SpamFree;
use Illuminate\Support\Facades\Gate;

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
    public function store($channelId, Thread $thread, CreatePostRequest $request)
    {
        if (Gate::denies('create', new Reply)) {
            return response(
                'You are posting too frequently. Please take a break.', 422
            );
        }
        return $thread->addReply([
            'body' => $request->body,
            'user_id' => auth()->id()
        ])->load('owner');
    }

    /**
     * update the reply.
     *
     * @param  mixed $request
     * @param  mixed $reply
     * @param  mixed $spam
     * @return void
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            $this->validate(request(), [
                'body' => [
                    'required',
                    new SpamFree()
                ]
                ]);

            $reply->update(request(['body']));
        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422);
        }
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
}

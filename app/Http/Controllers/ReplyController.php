<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($channelId, Request $request, Thread $thread)
    {
        $this->validate($request,[
            'body' => 'required'
        ]);


        $thread->addReply([
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        return back()->with('flash', 'Your reply has been left.');
    }
}

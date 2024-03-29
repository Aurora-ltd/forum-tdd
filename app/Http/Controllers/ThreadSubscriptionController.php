<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadSubscriptionController extends Controller
{
    public function store($channelId, Thread $thread)
    {
        $thread->subscribe(1);
    }

    public function destroy($channelId, Thread $thread)
    {
        $thread->unsubscribe();
    }
}

@extends('layouts.app')

@section('content')
<div class="container">
    {{-- @dd('thread.show'); --}}
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2">
            <div class="card">
                <div class="card-header">

                    <div class="level">
                        <span class="flex">
                            <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
                    posted:
                    {{ $thread->title }}
                    @if (Auth::check())
                        </span>
                        <form action="{{ $thread->path() }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-link">Delete Thread</button>
                        </form>
                    @endif
                    </div>
                </div>

                <div class="card-body">
                    {{$thread->body}}
                </div>
            </div>
        @foreach($replies as $reply)
            @include('threads.reply')
        @endforeach

        {{ $replies->links() }}

        @if(auth()->check())
            <form action="{{$thread->path().'/replies'}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name" class="col-form-label">{{ __('Reply') }}</label>
                    <textarea name="body" class="form-control" placeholder="Have Something to Say" rows="5"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Post</button>
            </form>
        @else
        <p class="text-center">Please  <a href="{{route('login')}}">sign in</a> to participate in this discussion</p>
        @endif
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <p>
                    This thread was published {{ $thread->created_at->diffForHumans() }} by <a href="#">{{ $thread->creator->name }}</a>, and currently has <span>{{ $thread->replies_count }} {{ \Illuminate\Support\Str::plural('comment', $thread->replies_count) }}</span>
                </p>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>
            {{ $user->name }}
        </h1>

        <small>
            Since {{ $user->created_at->diffForHumans() }}
        </small>
    </div>

        @foreach ($user->threads as $thread)
        <div class="card">
            <div class="card-header">
                <a href="{{ route('profile', $thread->creator->name) }}">{{ $thread->creator->name }}</a>
                posted:
                {{ $thread->title }}</div>

            <div class="card-body">
                {{$thread->body}}
            </div>
        </div>
        @endforeach
        {{ $threads->links() }}
</div>
@endsection
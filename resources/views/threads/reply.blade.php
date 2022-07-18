<div id="reply-{{ $reply->id }}" class="card mt-4">
    <div class="card-header">
        <div class="level">
            <h5 class="flex">
                <a href="{{ route('profile',$reply->owner->name) }}">
                    {{ $reply->owner->name }}
                </a>
                &nbsp;said
                {{ $reply->created_at->diffForHumans() }}
            </h5>

            <div>
                <form action="/replies/{{ $reply->id }}/favorites" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-light {{ $reply->isFavorited() ? 'disabled' : '' }}">
                        {{ $reply->favorites_count }} {{ \Illuminate\Support\Str::plural('Favorite', $reply->favorites_count) }}
                    </button>
                </form>
            </div>
        </div>
    </div>
        <div class="card-body">
            {{$reply->body}}
        </div>

        @can('update', $reply)
            <div class="card-footer">
                <form method="POST" action="/replies/{{ $reply->id }}">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        @endcan
</div>

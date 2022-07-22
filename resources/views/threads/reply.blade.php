<reply :attributes="{{ $reply }}" inline-template v-cloak>
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
                    <favorite :reply="{{ $reply }}"></favorite>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body">{{ $reply->body }}</textarea>
                </div>

                <button class="btn btn-info btn-sm mt-2" @click="update">Update</button>
                <button class="btn btn-dark btn-sm mt-2" @click="editing = false">Cancel</button>
            </div>

            <div v-else v-text="body"></div>
        </div>

        @can('update', $reply)
            <div class="card-footer level">
                <button class="btn btn-secondary btn-sm me-2" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-sm" @click="destroy">Delete</button>
            </div>
        @endcan
    </div>
</reply>

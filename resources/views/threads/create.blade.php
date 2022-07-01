@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Forum Threads') }}</div>
                <div class="card-body">
                    <form method="post" action="/threads">
                        @csrf
                        <div class="form-group">
                            <label for="channel">{{ __('Channel') }}</label>
                            <select class="form-control" id="channel" name="channel_id" required>
                                <option value="">Choose one...</option>
                                @foreach ($channels as $channel)
                                    <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>{{ $channel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{old('title')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="body">Body</label>
                            <textarea class="form-control" id="body" name="body" rows="3" required>{{old('body')}}</textarea>
                        </div>
                            <button type="submit" class="btn btn-primary">Publish</button>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

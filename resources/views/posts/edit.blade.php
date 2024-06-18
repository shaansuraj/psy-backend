@extends('layouts.app', ['page' => __('Post Edit'), 'pageSlug' => 'edit post'])

@section('content')
    <style>
        select option {
            background-color: #000 !important;
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Edit Post') }}</h5>
                </div>
                <form method="post" action="{{ route('post.update', ['post', $post->id]) }}" autocomplete="off">
                    <div class="card-body">
                        @csrf
                        @method('put')

                        @include('alerts.success')

                        <input type="hidden" name="id" value="{{ $post->id }}">

                        <div class="form-group{{ $errors->has('content') ? ' has-danger' : '' }}">
                            <label>{{ __('Content') }}</label>
                            <textarea style="min-height: 200px" name="content"
                                class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" cols="30" rows="10">{{ $post->content }}</textarea>
                            @include('alerts.feedback', ['field' => 'content'])
                        </div>

                        <label>{{ __('Images') }}</label>
                        <div style="width: 100%; overflow:auto" class="d-flex my-2">
                            @if ($post->images)
                                @foreach ($post->images as $image)
                                    <div style="width: 200px">
                                        <img style="width: 100%; height: auto"
                                            src="{{ asset('server/posts/images/' . $image->name) }}" alt="img">
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('verified') ? ' has-danger' : '' }}">
                            <label>{{ __('Verified?') }}</label>
                            <select class="form-control{{ $errors->has('verified') ? ' is-invalid' : '' }}"
                                name="verified">
                                <option value="0" @if ($post->verified == 0) selected @endif>No</option>
                                <option value="1" @if ($post->verified == 1) selected @endif>Yes</option>
                            </select>
                            @include('alerts.feedback', ['field' => 'verified'])
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

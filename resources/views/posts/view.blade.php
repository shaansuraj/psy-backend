@extends('layouts.app', ['page' => __('Post'), 'pageSlug' => 'post'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card py-2 px-4">
                <div class="d-flex">
                    <img width="40" height="40" src="/server/profile/{{ $post->user->profile }}" alt="image">
                    <div class="d-flex ml-2 flex-column">
                        <h4 class="mb-0">{{ $post->user->user_name }}</h4>
                        <h6>{{ $post->user->about }}</h6>
                    </div>
                </div>
                <div class="mt-2">
                    <p>{{ $post->content }}</p>
                </div>
                <div style="width: 100%; overflow:auto" class="d-flex my-2">
                    @if ($post->images)
                        @foreach ($post->images as $image)
                            <div style="width: 200px; padding: 0px 5px">
                                <img style="width: 100%; height: auto"
                                    src="{{ asset('server/posts/images/' . $image->name) }}" alt="img">
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="d-flex text-white">
                    <div class="mr-2"><i class="tim-icons text-primary icon-heart-2 pr-2"></i>{{ $post->likes_count }}
                    </div>
                    <div class="mr-2"><i class="tim-icons text-primary icon-chat-33 pr-2"></i>{{ $post->comments_count }}
                    </div>
                    <div class="mr-2"><i class="tim-icons text-primary icon-link-72 pr-2"></i>{{ $post->shares_count }}
                    </div>
                </div>

                <div class="d-flex mt-4">
                    {{-- @foreach ($post->comments as $item)

                    @endforeach --}}
                </div>
            </div>
        </div>
    </div>
@endsection

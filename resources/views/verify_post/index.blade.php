@extends('layouts.app', ['page' => __('Verfiy Post'), 'pageSlug' => 'verfiy post'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6 col-md-4 col-sm-2">
                            <h4 class="card-title">Verify Post</h4>
                        </div>
                        <div class="col-lg-6 col-md-8 col-sm-10 d-flex align-items-center justify-content-end text-right">
                            <form action="{{route('verifypost.search')}}" method="GET" class="d-flex align-items-center justify-content-center">
                                <input type="text" class="form-control" name="query" placeholder="Text..." />
                                <button type="submit" style="min-width: 80px" class="btn btn-sm btn-primary mx-2">Search</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-body-main">
                        <table class="table tablesorter " id="">
                            <thead class=" text-primary">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Content</th>
                                    <th scope="col">Posted By</th>
                                    <th scope="col">Likes</th>
                                    <th scope="col">Comments</th>
                                    <th scope="col">Shares</th>
                                    <th scope="col">Posting Date</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($posts as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <p style="line-break: anywhere">{{ $item->content }}</p>
                                        </td>
                                        <td>
                                            <a href="{{ route('appuser.show', ['appuser' => $item->user['id']]) }}">
                                                {{ $item->user['user_name'] }}
                                            </a>
                                        </td>
                                        <td>{{ $item->likes_count }}</td>
                                        <td>{{ $item->comments_count }}</td>
                                        <td>{{$item->shares_count}}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('M d Y') }}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item"
                                                        href="{{ route('post.show', ['post' => $item->id]) }}">Show</a>

                                                    <a class="dropdown-item"
                                                        href="{{ route('post.edit', ['post' => $item->id]) }}">Edit</a>

                                                    <form action="{{ route('post.destroy', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="dropdown-item">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end" aria-label="...">
                        {{ $posts->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection

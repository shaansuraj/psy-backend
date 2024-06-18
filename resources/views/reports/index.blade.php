@extends('layouts.app', ['page' => __('Reports'), 'pageSlug' => 'reports'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6 col-md-4 col-sm-2">
                            <h4 class="card-title">Reports</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="table-body-main">
                        <table class="table tablesorter" id="">
                            <thead class=" text-primary">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Item Type</th>
                                    <th scope="col">Item</th>
                                    <th scope="col">Reason</th>
                                    <th scope="col">By</th>
                                    <th scope="col">For</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Reported Date</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $item->reported_item_type }}
                                        </td>
                                        <td>
                                            @if ($item->reported_item_type == 'post')
                                                <a href="{{ route('post.show', ['post' => $item->reported_item_id]) }}">
                                                    View Post
                                                </a>
                                            @elseif ($item->reported_item_type == 'user')
                                                <a
                                                    href="{{ route('appuser.show', ['appuser' => $item->reported_item_id]) }}">
                                                    View User
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <p>{{ $item->reason }}</p>
                                        </td>
                                        <td>
                                            <a href="{{ route('appuser.show', ['appuser' => $item->user['id']]) }}">
                                                {{ $item->user['user_name'] }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('appuser.show', ['appuser' => $item->byUser['id']]) }}">
                                                {{ $item->byUser['user_name'] }}
                                            </a>
                                        </td>
                                        <td>
                                            @if ($item->status == 'pending')
                                                <span class="badge bg-danger">Pending</span>
                                            @else
                                                <span class="badge bg-primary">Solved</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('M d Y') }}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu  dropdown-menu-right dropdown-menu-arrow">
                                                    <form action="{{ route('report.destroy', $item->id) }}" method="POST">
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
                        {{ $reports->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app', ['page' => __('App Users'), 'pageSlug' => 'app users'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6 col-md-4 col-sm-2">
                            <h4 class="card-title">App Users</h4>
                        </div>
                        <div class="col-lg-6 col-md-8 col-sm-10 d-flex align-items-center justify-content-end text-right">
                            <form action="{{route('appuser.search')}}" method="GET" class="d-flex align-items-center justify-content-center">
                                <input type="text" name="query" class="form-control" placeholder="Name, Email, Number....">
                                <button type="submit" style="min-width: 80px"
                                    class="btn btn-sm btn-primary mx-2">Search</button>
                            </form>
                            <a href="/appuser/create" class="btn btn-sm btn-primary">Add user</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="table-body-main">
                        <table class="table tablesorter " id="">
                            <thead class=" text-primary">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Profile</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">SignUp Method</th>
                                    <th scope="col">Joining Date</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ asset('server/profile/' . $item->profile) }}" width="40px"
                                                height="40px" style="border-radius: 50%" alt="">
                                        </td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            <a href="mailto:{{ $item->email }}">{{ $item->email }}</a>
                                        </td>
                                        <td>
                                            {{ $item->user_name }}
                                        </td>
                                        <td>
                                            {{ $item->location }}
                                        </td>
                                        <td>
                                            @if ($item->provider == 'google')
                                                <span class="badge bg-primary">Google</span>
                                            @else
                                                <span class="badge bg-primary">Normal</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('M d Y') }}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item"
                                                        href="{{ route('appuser.show', ['appuser' => $item->id]) }}">Show</a>

                                                    <a class="dropdown-item"
                                                        href="{{ route('appuser.edit', ['appuser' => $item->id]) }}">Edit</a>

                                                    <form action="{{ route('appuser.destroy', $item->id) }}"
                                                        method="POST">
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
                        {{ $users->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection

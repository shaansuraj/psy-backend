@extends('layouts.app', ['pageSlug' => 'create role'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Create Role') }}</h5>
                </div>
                <form method="post" action="{{ route('role.store') }}" autocomplete="off">
                    <div class="card-body">
                        @csrf
                        @method('post')

                        @include('alerts.success')

                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <label>{{ __('Name') }}</label>
                            <input type="text" name="name"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Name') }}" value="{{ old('name') }}">
                            @include('alerts.feedback', ['field' => 'name'])
                        </div>

                        <div class="form-group{{ $errors->has('permissions') ? ' has-danger' : '' }}" style="width: 30%;">
                            <label>{{ __('Permissions') }}</label>
                            <div style="display: flex; flex-direction:column;">
                                @forelse ($permissions as $permission)
                                    <div>
                                        <input class="{{ $errors->has('permissions') ? ' is-invalid' : '' }}"
                                            type="checkbox" value="{{ $permission->id }}" name="permissions[]"
                                            id="permissions">
                                        <label for="permissions">{{ $permission->name }}</label>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                            @include('alerts.feedback', ['field' => 'permissions'])
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

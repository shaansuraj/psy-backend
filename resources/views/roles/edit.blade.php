@extends('layouts.app', ['pageSlug' => 'edit role'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Edit Role') }}</h5>
                </div>
                <form method="post" action="{{ route('role.update', $role->id) }}" autocomplete="off">
                    <div class="card-body">
                        @csrf
                        @method('put')

                        @include('alerts.success')

                        <input type="hidden" name="id" value="{{ $role->id }}">

                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <label>{{ __('Name') }}</label>
                            <input type="text" name="name"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Name') }}" value="{{ $role->name }}">
                            @include('alerts.feedback', ['field' => 'name'])
                        </div>

                        <div class="form-group{{ $errors->has('permissions') ? ' has-danger' : '' }}" style="width: 30%;">
                            <label>{{ __('Permissions') }}</label>
                            <div style="display: flex; flex-direction:column;">
                                @forelse ($permissions as $permission)
                                    <div>
                                        <input {{ in_array($permission->id, $rolePermissions ?? []) ? 'checked' : '' }}
                                            class="{{ $errors->has('permissions') ? ' is-invalid' : '' }}" type="checkbox"
                                            value="{{ $permission->id }}" name="permissions[]" id="permissions">
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

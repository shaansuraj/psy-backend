@extends('layouts.app', ['page' => __('App User Edit'), 'pageSlug' => 'edit user'])

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
                    <h5 class="title">{{ __('Edit User') }}</h5>
                </div>
                <form method="post" action="{{ route('appuser.update', ['appuser', $user->id]) }}" autocomplete="off">
                    <div class="card-body">
                        @csrf
                        @method('put')

                        @include('alerts.success')

                        <input type="hidden" name="id" value="{{ $user->id }}">

                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <label>{{ __('Name') }}</label>
                            <input type="text" name="name"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Name') }}" value="{{ $user->name }}">
                            @include('alerts.feedback', ['field' => 'name'])
                        </div>

                        <div class="form-group{{ $errors->has('user_name') ? ' has-danger' : '' }}">
                            <label>{{ __('User Name') }}</label>
                            <input type="text" name="user_name"
                                class="form-control{{ $errors->has('user_name') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('User Name') }}" value="{{ $user->user_name }}">
                            @include('alerts.feedback', ['field' => 'user_name'])
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <label>{{ __('Email address') }}</label>
                            <input type="email" name="email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Email address') }}" value="{{ $user->email }}">
                            @include('alerts.feedback', ['field' => 'email'])
                        </div>

                        <div class="form-group{{ $errors->has('about') ? ' has-danger' : '' }}">
                            <label>{{ __('About') }}</label>
                            <input type="text" name="about"
                                class="form-control{{ $errors->has('about') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('About') }}" value="{{ $user->about }}">
                            @include('alerts.feedback', ['field' => 'about'])
                        </div>

                        <div class="form-group{{ $errors->has('number') ? ' has-danger' : '' }}">
                            <label>{{ __('Number') }}</label>
                            <input type="number" name="number"
                                class="form-control{{ $errors->has('number') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Number') }}" value="{{ $user->number }}">
                            @include('alerts.feedback', ['field' => 'number'])
                        </div>

                        <div class="form-group{{ $errors->has('location') ? ' has-danger' : '' }}">
                            <label>{{ __('Location') }}</label>
                            <input type="text" name="location"
                                class="form-control{{ $errors->has('location') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Location') }}" value="{{ $user->location }}">
                            @include('alerts.feedback', ['field' => 'location'])
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

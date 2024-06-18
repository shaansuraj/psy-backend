@extends('layouts.app', ['page' => __('App User Create'), 'pageSlug' => 'create user'])

@section('content')
<style>
    select option{
        background-color: #000 !important;
    }
</style>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Create App User') }}</h5>
                </div>
                <form method="post" action="{{ route('user.store') }}" autocomplete="off">
                    <div class="card-body">
                        @csrf
                        @method('post')

                        @include('alerts.success')

                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <label>{{ __('Name *') }}</label>
                            <input type="text" name="name"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Name') }}" value="{{ old('name') }}">
                            @include('alerts.feedback', ['field' => 'name'])
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <label>{{ __('Email address *') }}</label>
                            <input type="email" name="email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Email address') }}" value="{{ old('email') }}">
                            @include('alerts.feedback', ['field' => 'email'])
                        </div>

                        <div class="form-group{{ $errors->has('number') ? ' has-danger' : '' }}">
                            <label>{{ __('Number') }}</label>
                            <input type="number" name="email"
                                class="form-control{{ $errors->has('number') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Number') }}" value="{{ old('number') }}">
                            @include('alerts.feedback', ['field' => 'number'])
                        </div>

                        <div class="form-group{{ $errors->has('user_name') ? ' has-danger' : '' }}">
                            <label>{{ __('Username *') }}</label>
                            <input type="text" name="user_name"
                                class="form-control{{ $errors->has('user_name') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Username') }}" value="{{ old('user_name') }}">
                            @include('alerts.feedback', ['field' => 'user_name'])
                        </div>

                        <div class="form-group{{ $errors->has('location') ? ' has-danger' : '' }}">
                            <label>{{ __('Location') }}</label>
                            <input type="text" name="location"
                                class="form-control{{ $errors->has('location') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Location') }}" value="{{ old('location') }}">
                            @include('alerts.feedback', ['field' => 'location'])
                        </div>

                        <div class="form-group{{ $errors->has('about') ? ' has-danger' : '' }}">
                            <label>{{ __('About') }}</label>
                            <input type="text" name="about"
                                class="form-control{{ $errors->has('about') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('About') }}" value="{{ old('about') }}">
                            @include('alerts.feedback', ['field' => 'about'])
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <label>{{ __('Password *') }}</label>
                            <input type="password" name="password"
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Password') }}" value="" required>
                            @include('alerts.feedback', ['field' => 'password'])
                        </div>

                        <div class="form-group">
                            <label>{{ __('Confirm Password *') }}</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="{{ __('Confirm Password') }}" value="" required>
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

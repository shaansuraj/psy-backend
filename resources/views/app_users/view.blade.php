@extends('layouts.app', ['page' => __('App User Profile'), 'pageSlug' => 'edit user'])

@section('content')
    <style>
        select option {
            background-color: #000 !important;
        }


        .profile-user-info {
            display: table;
            width: 98%;
            width: calc(100% - 24px);
            margin: 0 auto
        }

        .profile-info-row {
            display: table-row
        }

        .profile-info-name,
        .profile-info-value {
            display: table-cell;
            border-top: 1px dotted #FFF
        }

        .profile-info-name {
            padding: 6px 10px 6px 4px;
            font-weight: 400;
            color: rgb(203, 196, 196);
            background-color: transparent;
            width: 110px;
            vertical-align: middle
        }

        .profile-info-value {
            padding: 6px 4px 6px 6px;
            color: #FFF
        }

        .profile-info-value>span+span:before {
            display: inline;
            content: ",";
            margin-left: 1px;
            margin-right: 3px;
            color: #666;
            border-bottom: 1px solid #FFF
        }

        .profile-info-value>span+span.editable-container:before {
            display: none
        }

        .profile-info-row:first-child .profile-info-name,
        .profile-info-row:first-child .profile-info-value {
            border-top: none
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Profile') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3 center">
                            <span class="profile-picture d-flex justify-content-center">
                                <img class="editable img-responsive" alt=" Avatar" id="avatar2"
                                    src="{{asset('server/profile/'.$user->profile)}}">
                            </span>

                            <div class="space space-4"></div>

                            @can('appuser.edit')
                                <a href="{{ route('appuser.edit', ['appuser' => $user->id]) }}" class="btn btn-sm py-2 btn-block btn-primary">
                                    <span class="bigger-110">Edit User</span>
                                </a>
                            @endcan
                        </div><!-- /.col -->

                        <div class="col-xs-12 col-sm-9">
                            <h4 class="text-white">
                                <span class="middle">{{ $user->name }}</span>
                            </h4>

                            <div class="profile-user-info">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Username </div>

                                    <div class="profile-info-value">
                                        <span>{{ $user->user_name }}</span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Location </div>

                                    <div class="profile-info-value">
                                        <i class="fa fa-map-marker bigger-110"></i>
                                        <span>{{ $user->location }}</span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Number </div>

                                    <div class="profile-info-value">
                                        <span>{{ $user->number }}</span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Country </div>

                                    <div class="profile-info-value">
                                        <span>{{ $user->country }}</span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Joined </div>

                                    <div class="profile-info-value">
                                        <span>{{ \Carbon\Carbon::parse($user->created_at)->format('M d Y') }}</span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="space-20"></div>

                    <div class="row">
                        <div class="col-xs-12 ml-1 mt-3 col-sm-6">
                            <div class="widget-box transparent">
                                <div class="widget-header widget-header-small">
                                    <h4 class="widget-title smaller">
                                        About
                                    </h4>
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main">
                                        <p>
                                            {{$user->about}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

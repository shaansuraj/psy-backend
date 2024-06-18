<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-mini">{{ __('PSY') }}</a>
            <a href="#" class="simple-text logo-normal">{{ __('Dashboard') }}</a>
        </div>
        <ul class="nav">
            <li @if ($pageSlug == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            {{-- <li>
                <a data-toggle="collapse" href="#laravel-examples" aria-expanded="false">
                    <i class="fab fa-laravel"></i>
                    <span class="nav-link-text">{{ __('Laravel Examples') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse" id="laravel-examples">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'profile') class="active " @endif>
                            <a href="{{ route('profile.edit') }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('User Profile') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="{{ route('user.index') }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ __('User Management') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li> --}}
            @can('view-user')
                <li @if ($pageSlug == 'users') class="active " @endif>
                    <a href="{{ route('user.index') }}">
                        <i class="tim-icons icon-single-02"></i>
                        <p>{{ __('Users') }}</p>
                    </a>
                </li>
            @endcan
            @can('view-app-user')
                <li @if ($pageSlug == 'app users') class="active " @endif>
                    <a href="{{ route('appuser.index') }}">
                        <i class="tim-icons icon-badge"></i>
                        <p>{{ __('App Users') }}</p>
                    </a>
                </li>
            @endcan
            @can('view-role')
                <li @if ($pageSlug == 'roles') class="active " @endif>
                    <a href="{{ route('role.index') }}">
                        <i class="tim-icons icon-notes"></i>
                        <p>{{ __('Roles') }}</p>
                    </a>
                </li>
            @endcan
            @can('view-posts')
                <li @if ($pageSlug == 'posts') class="active " @endif>
                    <a href="{{ route('post.index') }}">
                        <i class="tim-icons icon-image-02"></i>
                        <p>{{ __('Posts') }}</p>
                    </a>
                </li>
            @endcan
            @can('verify-posts')
                <li @if ($pageSlug == 'verfiy post') class="active " @endif>
                    <a href="{{ route('post.verify') }}">
                        <i class="tim-icons icon-image-02"></i>
                        <p>{{ __('Verify Posts') }}</p>
                    </a>
                </li>
            @endcan
            @can('view-reports')
                <li @if ($pageSlug == 'reports') class="active " @endif>
                    <a href="{{ route('reports') }}">
                        <i class="tim-icons icon-alert-circle-exc"></i>
                        <p>{{ __('Reports') }}</p>
                    </a>
                </li>
            @endcan
        </ul>
    </div>
</div>

<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="index.html"><span class="align-middle">FYP</span></a>
        <ul class="sidebar-nav">

            <div class="sidebar-user">
                <div class="d-flex justify-content-center">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('img/default icon.jpg') }}" class="avatar img-fluid rounded me-1" alt="User icon" />
                    </div>
                    <div class="flex-grow-1 ps-2">
                        <a class="sidebar-user-title dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        {{ __($auth_user['name']) }}
                        </a>
                    </div>
                </div>
            </div>

            <li class="sidebar-item active">
                <a class="sidebar-link" href="{{ route('panel') }}"><i class="align-middle" data-feather="sliders"></i> <span class="align-middle">{{ __('Dashboard') }}</span></a>
            </li>
            
            <li class="sidebar-header">
                <span class="align-middle">{{ __('Delivery Order') }}</span>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('cms.list', ['model' => 'order']) }}"><i class="align-middle" data-feather="user"></i> <span class="align-middle">{{ __('View All Orders') }}</span></a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href=""><i class="align-middle" data-feather="log-in"></i> <span class="align-middle">{{ __('Create Order') }}</span></a>
            </li>

            <li class="sidebar-header">
                {{ __('Order Groups') }}
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('cms.list', ['model' => 'group']) }}"><i class="align-middle" data-feather="square"></i> <span class="align-middle">{{ __('View All Order Groups') }}</span></a>
            </li>

            <li class="sidebar-header">
                {{ __('Staff Account Management') }}
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('cms.list', ['model' => 'staff']) }}"><i class="align-middle" data-feather="square"></i> <span class="align-middle">{{ __('View All Staff Accounts') }}</span></a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href=""><i class="align-middle" data-feather="square"></i> <span class="align-middle">{{ __('Create Staff Account') }}</span></a>
            </li>

        </ul>
        <div class="sidebar-cta">
            <div class="sidebar-cta-content">
                <div class="d-grid">
                    <a href="{{ route('logout') }}" class="btn btn-primary">{{ __('Logout') }}</a>
                </div>
            </div>
        </div>
    </div>
</nav>


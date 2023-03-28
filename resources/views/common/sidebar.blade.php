@php
$account = auth()->user();
if(Cookie::has('company')){
	$company = json_decode(Cookie::get('company'), true);
}
@endphp

<nav id="sidebar" class="sidebar js-sidebar">
	<div class="sidebar-content js-simplebar">
		<a class="sidebar-brand" href="index.html"><span class="align-middle">FYP</span></a>

		<div class="sidebar-user">
			<div class="d-flex justify-content-center">
				<div class="flex-shrink-0">
					<img src="{{ Auth::user()->getUserProfilePicture() }}" class="avatar img-fluid rounded me-1" alt="User icon" />
				</div>
				<div class="flex-grow-1 ps-2">
					<a class="sidebar-user-title dropdown-toggle" href="#" data-bs-toggle="dropdown">
						{{ __($account->first_name . " " . $account->last_name) }}	
					</a>
					<div class="dropdown-menu dropdown-menu-start">
						@includeIf('common/personal_menu')
					</div>
					<div class="sidebar-user-subtitle">{{ __($company['company_name']) }}</div>
				</div>
			</div>
		</div>

		<ul class="sidebar-nav">

			<li class="sidebar-item {{ (request()->routeIs('panel'))?'active':'' }}">
				<a class="sidebar-link" href="{{ route('panel') }}"><i class="align-middle" data-feather="sliders"></i> <span class="align-middle">{{ __('Dashboard') }}</span></a>
			</li>
			
			<li class="sidebar-header">
				<span class="align-middle">{{ __('Delivery Order') }}</span>
			</li>

			<li class="sidebar-item {{ (request()->routeIs('cms.list') && request()->route('model') == 'order')?'active':'' }}">
				<a class="sidebar-link" href="{{ route('cms.list', ['model' => 'order']) }}"><i class="align-middle" data-feather="package"></i> <span class="align-middle">{{ __('View All Orders') }}</span></a>
			</li>

			<li class="sidebar-item {{ (request()->routeIs('cms.create') && request()->route('model') == 'order')?'active':'' }}">
				<a class="sidebar-link" href="{{ route('cms.create', ['model' => 'order']) }}"><i class="align-middle" data-feather="plus-circle"></i> <span class="align-middle">{{ __('Create Order') }}</span></a>
			</li>

			<li class="sidebar-header">
				{{ __('Task') }}
			</li>

			<li class="sidebar-item {{ (request()->routeIs('cms.list') && request()->route('model') == 'task')?'active':'' }}">
				<a class="sidebar-link" href="{{ route('cms.list', ['model' => 'task']) }}"><i class="align-middle" data-feather="truck"></i> <span class="align-middle">{{ __('Route Planning') }}</span></a>
			</li>

			<li class="sidebar-header">
				{{ __('Staff Account Management') }}
			</li>

			<li class="sidebar-item {{ (request()->routeIs('cms.list') && request()->route('model') == 'account')?'active':'' }}">
				<a class="sidebar-link" href="{{ route('cms.list', ['model' => 'account']) }}"><i class="align-middle" data-feather="user"></i> <span class="align-middle">{{ __('View All Staff Accounts') }}</span></a>
			</li>

			<li class="sidebar-item {{ (request()->routeIs('cms.create') && request()->route('model') == 'account')?'active':'' }}">
				<a class="sidebar-link" href="{{ route('cms.create', ['model' => 'account']) }}"><i class="align-middle" data-feather="plus-circle"></i> <span class="align-middle">{{ __('Create Staff Account') }}</span></a>
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

@push('scripts')
<script type="text/javascript">
var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
  	return new bootstrap.Dropdown(dropdownToggleEl);
})
</script>
@endpush

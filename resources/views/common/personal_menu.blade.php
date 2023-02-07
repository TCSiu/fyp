@php
$account = auth()->user();
@endphp
<a class="dropdown-item" href="{{ route('cms.view', ['model' => 'account', 'id' => $account->id]) }}">
	<i class="align-middle me-1" data-feather="user"></i> {{ __('Profile') }}
</a>
<a class="dropdown-item" href="{{ route('cms.view', ['model' => 'company', 'id' => $account->company_id]) }}">
	<i class="align-middle me-1" data-feather="truck"></i> {{ __('Company') }}
</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{ route('logout') }}">
	{{ __('Logout') }}
</a>
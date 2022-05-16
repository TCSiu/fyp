@php
if(Cookie::has('access_token')){
	$token = Cookie::get('access_token');
}
if(Cookie::has('auth_user')){
	$cookie_user = Cookie::get('auth_user');
	$auth_user = json_decode($cookie_user, true);
}
@endphp
@extends('layouts/default')

@section('content')
<main class="content">
	<div class="container-fluid p-0">
		<h1 class="h3 mb-3">{{ __('Content Management System') }}</h1>
		<div class="row justify-content-center">
			<div class="col-12 col-xl-10">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title">{{ __($inpage_title) }}</h5>
					</div>
					@includeIf('panel/views/' . $model)
				</div>
			</div>
		</div>
	</div>
</main>
@stop

@hasSection('form-js')
	@yield('form-js')
@endif
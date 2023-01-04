@php
if(Cookie::has('access_token')){
	$token = Cookie::get('access_token');
}
@endphp
@extends('layouts/default')

@section('content')
<main class="content">
	<div class="container-fluid p-0">
		<h1 class="h3 mb-3">{{ __('Dashboard') }}</h1>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title mb-0">{{ __('Dashboard') }}</h5>
					</div>
					<div class="card-body">
						{{ __($token) }}
					</div>
				</div>
			</div>
		</div>

	</div>
</main>
@stop
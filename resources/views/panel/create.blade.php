@php
if(!(isset($msg) && is_array($msg) && sizeOf($msg) > 0)){
	$temp = session('msg');
	if(isset($temp) && is_array($temp) && sizeOf($temp) > 0){
		$msg = session('msg');
	}
}
@endphp
@extends('common/default')

@section('content')
<main class="content">
	<div class="container-fluid p-0">
		<h1 class="h3 mb-3">{{ __('Content Management System') }}</h1>
		<div class="row justify-content-center">
		@if(isset($msg) && is_array($msg) && sizeOf($msg) > 0)
		{{ View::make('panel/part/alert', [$msg['type'] => $msg['message']]) }}
		@endif	
			<div class="col-12 col-xl-10">
				<div class="card">
					<div class="card-header">
						<h3 class="text-center">{{ __($inpage_title) }}</h3>
					</div>
					@includeIf('panel/forms/' . $model)
				</div>
			</div>
		</div>
	</div>
</main>
@stop

@hasSection('form-js')
	@yield('form-js')
@endif
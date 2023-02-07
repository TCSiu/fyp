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
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title">{{ __($inpage_title) }}</h5>
						@if(isset($data))
							<h6 class="card-subtitle text-muted">
								@if($total_count == 0)
									{{ __('There is no records yet.') }}
								@else
									{{ __('Showing :count of :total records.', ['count' => sizeof($data), 'total' => intval($total_count)]) }}
								@endif
							</h6>
							{{ View::make('common/operation', ['model'	=>	$model, 'operations'	=>	$operations]) }}
						@else
							<h6 class="card-subtitle text-muted">{{ __('An error has occured!') }}</h6>
						@endif
					</div>
					@includeIf('common/default_list')
				</div>
			</div>
		</div>
	</div>
</main>
@stop

@hasSection('form-js')
	@yield('form-js')
@endif
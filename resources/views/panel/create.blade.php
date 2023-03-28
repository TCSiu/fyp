@extends('common/default')

@section('content')
<main class="content">
	<div class="container-fluid p-0">
		<h1 class="h3 mb-3">{{ __('Content Management System') }}</h1>
		<div class="row justify-content-center">
			@includeIf('panel/part/alert')
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
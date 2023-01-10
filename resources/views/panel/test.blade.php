@php
$account = auth()->user();
@endphp
@extends('layouts/default')

@section('content')
<main class="content">
	<div class="container-fluid p-0">
		<h1 class="h3 mb-3">{{ __('Content Management System') }}</h1>
		<div class="row justify-content-center">
			<div class="col-12 col-xl-12">
				<div class="card">
					<div class="card-header">
						<h3 class="text-center">{{ __('Test') }}</h3>
					</div>
					<div class="col-12">
						<div class="border-primary bg-light dropzone" id="upload-dropzone"></div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</main>
@stop

@push('scripts')
<script>
Dropzone.autoDiscover = false;
let dropzone = new Dropzone("#upload-dropzone", {
	url: "{{ route('import', ['model' => 'order', 'id' => $account->id]) }}",
	method: "POST",
	parallelUploads: 20,
	maxFilesize: 1,
	paramName: "file",
	init: function(){

	}
});
</script>
@endpush
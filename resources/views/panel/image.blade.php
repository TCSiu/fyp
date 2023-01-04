@php

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
<script type="text/javascript">
Dropzone.autoDiscover = false;
var fileList = new Array;
let dropzone = new Dropzone("#upload-dropzone", {
	url: "{{ route('upload') }}",
    addRemoveLinks: true,
	method: "POST",
	parallelUploads: 20,
	maxFilesize: 1,
	paramName: "file",
});
</script>
@endpush
@hasSection('form-js')
	@yield('form-js')
@endif






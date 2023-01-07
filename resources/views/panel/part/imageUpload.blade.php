@php

@endphp

<button type="button" class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#imageSelect">
{{ __('Select Image') }}
</button>

<div class="modal fade" id="imageSelect" tabindex="-1" aria-labelledby="imageSelectLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageSelectLabel">Select a Image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="border">

        </div>
        <hr>
        <div class="border-primary bg-light dropzone" id="upload-dropzone"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>



@push('scripts')
<script type="text/javascript">
Dropzone.autoDiscover = false;
var fileList = new Array;
let dropzone = new Dropzone("#upload-dropzone", {
	url: "{{ route('upload') }}",
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






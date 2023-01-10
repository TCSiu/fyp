@php
$account = auth()->user();
@endphp

@isset($operations)
<div class="mt-3">
@foreach($operations as $operation)
@if(strcmp($operation, 'create') == 0)
<a class="btn btn-success white-space-nowrap" href="{{ route('cms.create', ['model' => $model]) }}" id="btn_create">
    <i class="align-middle me-2" data-feather="plus"></i>{{ __('Create New') }}
</a>
@endif
@if(strcmp($operation, 'import_csv') == 0)
<a class="btn btn-info white-space-nowrap" href="{{ route('cms.create', ['model' => $model]) }}" id="btn_select_image"  data-bs-toggle="modal" data-bs-target="#modal_import_csv">
    <i class="align-middle me-2" data-feather="plus"></i>{{ __('Import CSV') }}
</a>

<div class="modal fade" id="modal_import_csv" tabindex="-1" aria-labelledby="modal_import_csv_label" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_import_csv_label">Upload a CSV</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="border-primary bg-light dropzone" id="upload-dropzone"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn_save_image" data-bs-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>
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
    this.on('error', function(file, response) {
      if (response['success'] == false) {
        console.log(response['data']);
        var errorDisplay = document.querySelectorAll('[data-dz-errormessage]');
        errorDisplay[errorDisplay.length - 1].innerHTML = response['data'];
      }
    });
	}
});
</script>
@endpush
@endif

@if(strcmp($operation, 'gen_csv') == 0)
<a class="btn btn-secondary white-space-nowrap" href="{{ route('cms.get_csv', ['model' => $model]) }}" id="btn_gen_csv">
    <i class="align-middle me-2" data-feather="plus"></i>{{ __('Generate CSV') }}
</a>
@endif
@endforeach
</div>
@endif
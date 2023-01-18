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
	url: "{{ route('import') }}",
	method: "POST",
  params: {"modal": "order", "id":"{{ $account->id }}"},
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

@if(strcmp($operation, 'route_planning') == 0)
@includeif('panel/part/loading')

<button class="btn btn-secondary white-space-nowrap" id="btn_route_planning">
  <i class="align-middle me-2" data-feather="plus"></i>{{ __('Route Planning') }}
</button>

<!-- <button type="button" class="btn btn-primary" id="btn_route_prepare" data-bs-toggle="modal" data-bs-target="#route_planning_requirement_modal">
  <i class="align-middle me-2" data-feather="plus"></i>{{ __('Route Planning') }}
</button>

<div class="modal fade" id="route_planning_requirement_modal" tabindex="-1" aria-labelledby="route_planning_requirement_modal_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content align">
      <div class="modal-header">
        <h5 class="modal-title" id="route_planning_requirement_modal_label">{{ __('Select Route Planning Requirement') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <select class="form-select" id="select_route_prepare_date" aria-label="Default select example">
        <option selected>Select a Date</option>
        
      </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button class="btn btn-secondary white-space-nowrap" id="btn_route_planning">
          {{ __('Submit') }}
        </button>
      </div>
    </div>
  </div>
</div> -->

<div class="modal" tabindex="-1" id="route_planning_modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('Route Planning Result') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="preview_modal_body">
        <div class="accordion accordion-flush" id="preview_planning_accordion">

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn_route_storing">Confirm</a>
      </div>
    </div>
  </div>
</div>

<template id="template_accordion">
  <h2 class="accordion-header" id="preview_planning_header_%id%">
    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#preview_planning_%id%" aria-expanded="false" aria-controls="preview_planning_%id%">
      {{ __('%id%') }}
    </button>
  </h2>
  <div id="preview_planning_%id%" class="accordion-collapse collapse" aria-labelledby="preview_planning_header_%id%" data-bs-parent="#preview_planning_accordion">
    <div class="accordion-body">
      <table class="table table-striped table-secondary" id="route_planning_table">
        <thead>
          <th scope="col">#</th>
          <th scope="col">Route</th>
        </thead>
        <tbody id="preview_tbody_%id%">
          
        </tbody>
      </table>
    </div>
  </div>
</template>

<template id="template_preview_row">
  <td>
    <span class="preview_id">{{ __('%id%') }}</span>
  </td>
  <td id="preview_route_%id%"></td>
</template>

@push('scripts')
<script>
window.addEventListener('DOMContentLoaded', function(){
  let loading = document.getElementById('loading');
  let route_planning_modal = new bootstrap.Modal(document.getElementById('route_planning_modal'));
  let data;
  function routePlanning(){
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
          let json = JSON.parse(xhr.responseText)
          loading.style.display = 'none';
          route_planning_modal.show();
          routePreview(json.data);
        }else if(this.readyState == 4 && this.status == 500){
          loading.style.display = 'none';
        }else if(this.readyState == 1){
          loading.style.display = 'flex';
        }
    }
    xhr.open("POST", "{{ route('route.planning') }}", true);
    xhr.send();
  }

  function routePreview(json = ''){
    data = JSON.parse(json.replaceAll('\'', '\"'));
    let count_accordion = 0;
    let preview_planning_accordion      = document.getElementById('preview_planning_accordion');
    let template_accordion              = document.getElementById('template_accordion');
    let template_row                    = document.getElementById('template_preview_row');
    preview_planning_accordion.innerHTML = '';
    for(let key in data){
      let count_row = 0;
      value = data[key];
      let temp_accordion = document.createElement('div');
      temp_accordion.classList.add('accordion-item');
      temp_accordion.innerHTML = template_accordion.innerHTML.replaceAll(/\%id\%/gi, ++count_accordion);
      preview_planning_accordion.appendChild(temp_accordion);
      for(let kkey in value){
        let tbody = document.getElementById('preview_tbody_' + count_accordion);
        let temp = document.createElement('tr');
        temp.innerHTML = template_row.innerHTML.replaceAll(/\%id\%/gi, ++count_row);
        tbody.appendChild(temp);
        let preview_route = document.querySelector('#preview_tbody_' + count_accordion + ' #preview_route_' + count_row);
        preview_route.innerText = value[kkey].id;
      }
      feather.replace();
    }
  }

  function routeStoring(){
    let xhr = new XMLHttpRequest();
    company_id = {{ $account->company_id }};
    let params = JSON.stringify({'data': data, 'company_id':company_id});
    xhr.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
          
        }
    }
    xhr.open("POST", "{{ route('route.storing') }}", true);
    xhr.setRequestHeader('content-type', 'application/json');
    xhr.send(params);
  }

	let btn_route_planning = document.getElementById('btn_route_planning');
	btn_route_planning.addEventListener('click', routePlanning);

  let btn_route_storing = document.getElementById('btn_route_storing');
	btn_route_storing.addEventListener('click', routeStoring);
});

</script>
@endpush
@endif
@endforeach
</div>
@endif
@php
$account = auth()->user();
@endphp

<button type="button" class="btn btn-primary ms-2" id="btn_select_image" data-bs-toggle="modal" data-bs-target="#modal_select_image">
{{ __('Select Image') }}
</button>

<div class="modal fade" id="modal_select_image" tabindex="-1" aria-labelledby="modal_select_image_label" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_select_image_label">Select a Image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="border p-2">
          <div class="row" id="image_lib"></div>

        </div>
        <div class="hr-or"></div>
        <div class="border-primary bg-light dropzone" id="upload-dropzone"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn_save_image" data-bs-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>

<template id="template_image_lib">
  <input type="radio" name="image_selection" id="image_selection_%id%" data-target="image_%id%" value="" />
  <label for="image_selection_%id%"><img src="" name="image[%id%]" id="image_%id%" class="img-thumbnail" alt="" style="width:150px;height:auto;" /></label>
</template>



@push('scripts')
<script>
Dropzone.autoDiscover = false;
document.addEventListener('DOMContentLoaded', function(){
  const btn_select_image = document.getElementById('btn_select_image');
  const btn_save_image = document.getElementById('btn_save_image');

  let profile_icon = document.getElementById('profile_icon');
  let template = document.getElementById('template_image_lib');
  let image_lib = document.getElementById('image_lib');
  let img, selection, lib = [], btn_id = 0;

  function getUniqueArray(arr1, arr2){
    let temp = [];
    arr1.forEach(value => temp.push(value.id));
    let res = arr2.filter(function(value){
      if(!temp.includes(value.id)){
        return true;
      }
    });
    return res;
  }

  function getImage(){
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
      if(this.readyState == 4 && this.status == 200){
        let json = JSON.parse(xhr.responseText);
        let data = json.data;
        if(Array.isArray(data)){
          lib = getUniqueArray(lib, data);
          lib.forEach(function(item, index){
            let temp = document.createElement('div');
            temp.classList.add('col-3', 'd-flex', 'justify-content-center', 'align-items-center');
            temp.innerHTML = template.innerHTML.replaceAll(/\%id\%/gi, btn_id);
            img = temp.querySelectorAll('img[id^=image]')[0];
            selection = temp.querySelectorAll('input[type=radio][id^=image_selection]')[0];
            img.src = item.path;
            img.alt = item.image;
            selection.value = item.id;
            image_lib.appendChild(temp);
            btn_id ++;
          });
          lib = data;
        }
      }
    }
    xhr.open("GET", "{{ route('getImageInventory', ['company_id' => $account->company_id]) }}", true);
    xhr.send();
  }

  let dropzone = new Dropzone("#upload-dropzone", {
    url: "{{ route('upload') }}",
    method: "POST",
    params: {"id":"{{ $account->id }}"},
    parallelUploads: 20,
    maxFilesize: 1,
    paramName: "file",
    init: function(){
      this.on('complete', function(file){
        getImage();
      });
    }
  });

  btn_select_image.addEventListener('click', getImage);

  btn_save_image.addEventListener('click', function(){
    let selected_radio = document.querySelector('input[name="image_selection"]:checked');
    let target = selected_radio.getAttribute('data-target');
    let selected_img = document.getElementById(target);
    profile_icon.src = selected_img.src;
  });

});
</script>
@endpush
@hasSection('form-js')
	@yield('form-js')
@endif






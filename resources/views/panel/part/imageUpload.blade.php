@php

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
  <input type="radio" name="image_selection" id="image_selection_%id%" data-target="image_%id%" />
  <label for="image_selection_%id%"><img src="" name="image[%id%]" id="image_%id%" class="img-thumbnail" alt="" data-img="" /></label>
</template>



@push('scripts')
<script>
Dropzone.autoDiscover = false;
window.addEventListener('DOMContentLoaded', function(){
  const btn_select_image = document.getElementById('btn_select_image');
  const btn_save_image = document.getElementById('btn_save_image');

  let profile_icon = document.getElementById('profile_icon');
  let template = document.getElementById('template_image_lib');
  let image_lib = document.getElementById('image_lib');
  let img;

  function getImage(){
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
      if(this.readyState == 4 && this.status == 200){
        let json = JSON.parse(xhr.responseText);
        let data = json.data;
        image_lib.innerHTML = "";
        if(Array.isArray(data)){
          data.forEach(function(item, index){
            let temp = document.createElement('div');
            temp.classList.add('col-3');
            temp.innerHTML = template.innerHTML.replaceAll(/\%id\%/gi, index);
            img = temp.querySelectorAll('img[id^=image]')[0];
            img.src = item.path;
            img.alt = item.image;
            img.dataset.img = item.id;
            image_lib.appendChild(temp);
          })
        }
      }
    }
    xhr.open("GET", "{{ route('getImageInventory') }}", true);
    xhr.send();
  }

  let dropzone = new Dropzone("#upload-dropzone", {
    url: "{{ route('upload') }}",
    method: "POST",
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
    profile_icon.dataset.img = selected_img.dataset.img;
  });

});
</script>
@endpush
@hasSection('form-js')
	@yield('form-js')
@endif






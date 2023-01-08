@php

@endphp

<button type="button" class="btn btn-primary ms-2" id="selectImage" data-bs-toggle="modal" data-bs-target="#imageSelect">
{{ __('Select Image') }}
</button>

<div class="modal fade" id="imageSelect" tabindex="-1" aria-labelledby="imageSelectLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageSelectLabel">Select a Image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="border" id="imageDisplay">

        </div>
        <div class="hr-or"></div>
        <div class="border-primary bg-light dropzone" id="upload-dropzone"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<template id="template_image_lib">
  <div class="col-3">
    <input type="radio" name="image[%id%]" id="image_%id%">
    <label for="image_%id%"><img src="" class="img-thumbnail" alt=""></label>
  </div>
</template>



@push('scripts')
<script>
Dropzone.autoDiscover = false;
window.addEventListener('DOMContentLoaded', function(){
  const selectImage = document.getElementById('selectImage');

  function getImage(){
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
      if(this.readyState == 4 && this.status == 200){
        let json = JSON.parse(xhr.responseText);
        let data = json.data;
        let imageDisplay = document.getElementById('imageDisplay');
        console.log(data);
        if(Array.isArray(data)){
          imageDisplay.innerHTML = "";
          data.forEach(function(item){
            let img = document.createElement('img');
            img.src = item.path;
            img.alt = item.image;
            img.className += "col-2 img-thumbnail";
            imageDisplay.appendChild(img);
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

  selectImage.addEventListener('click', getImage);

});
</script>
@endpush
@hasSection('form-js')
	@yield('form-js')
@endif






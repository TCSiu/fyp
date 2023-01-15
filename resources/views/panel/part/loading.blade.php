<div id="loading" style="display: none; justify-content: center; align-items: center; background-color: black; position:fixed; top: 0px; left:0px; z-index: 9999; width: 100%; height: 100%; opacity: .75;">
    <div class="la-ball-fussion la-2x">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>

@push('scripts')
<script>
window.addEventListener('DOMContentLoaded', function(){
	let btn_gen_csv = document.getElementById('btn_gen_csv');
	btn_gen_csv.addEventListener('click', testPy);
});

function testPy(){
	let xhr = new XMLHttpRequest();
    let loading = document.getElementById('loading');
    xhr.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            loading.style.display = 'none';
        }else if(this.readyState == 1){
            loading.style.display = 'flex';
        }
    }
    xhr.open("GET", "{{ route('testPy') }}", true);
    xhr.send();
}
</script>
@endpush
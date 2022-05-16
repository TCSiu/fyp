<script src="{{ secure_asset('https://code.jquery.com/jquery-3.6.0.js') }}" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="{{ secure_asset('https://code.jquery.com/ui/1.13.1/jquery-ui.js') }}" integrity="sha256-6XMVI0zB8cRzfZjqKcD01PBsAy3FlDASrlC8SxCpInY=" crossorigin="anonymous"></script>
<script src="{{ secure_asset('https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js') }}"></script>
<script src="{{ secure_asset('https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js') }}" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="{{ secure_asset('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ secure_asset('js/app.js') }}"></script>
@push('scripts')
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function(e){
	feather.replace();
	$('.datepicker').datepicker({format : 'yyyy-mm-dd', startDate: '0d',});
});
</script>
@endpush
@stack("scripts")
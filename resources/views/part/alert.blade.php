@if(isset($errors) && is_iterable($errors) && sizeof($errors) > 0)
<div class="alert alert-danger alert-dismissible fade show w-75 position-absolute top-5 start-50 translate-middle-x" role="alert">
	<div class="alert-message">		
	@foreach ($errors as $error)
	{{ __($error) }}<br/>
	@endforeach
	</div>
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
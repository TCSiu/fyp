@if(isset($errors) && is_iterable($errors) && sizeof($errors) > 0)
<div class="alert alert-danger alert-dismissible fade show w-75" role="alert">
    <div class="alert-message">	
    @foreach ($errors as $error)
	{{ __($error) }}<br/>
	@endforeach
    </div>
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(isset($success) && is_string($success) && strlen($success) > 0)
<div class="alert alert-success alert-dismissible fade show w-75" role="alert">
	<div class="alert-message">		
	{{ __($success) }}<br/>
	</div>
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
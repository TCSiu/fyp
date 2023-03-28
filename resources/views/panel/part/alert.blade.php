@if(session()->has('msg'))
@if(strcmp(session('msg.type'), 'errors') === 0)
<div class="alert alert-danger alert-dismissible fade show w-75" role="alert">
    <div class="alert-message">	
    @foreach (session('msg.message') as $error)
	{{ __($error) }}<br/>
	@endforeach
    </div>
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@elseif(strcmp(session('msg.type'), 'success') === 0)
<div class="alert alert-success alert-dismissible fade show w-75" role="alert">
	<div class="alert-message">		
	{{ session('msg.success') }}<br/>
	</div>
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@endif

@if(session()->has('msg.success'))
<div class="alert alert-success alert-dismissible fade show w-75" role="alert">
	<div class="alert-message">		
	{{ session('msg.success') }}<br/>
	</div>
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
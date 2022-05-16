<div class="card-body">
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				@foreach($target_fields as $field)
					<th>{{ $field }}</th>
				@endforeach
				<th>{{ __('actions') }}</th>
			</thead>
			<tbody>
				@foreach($data as $item)
					<tr>
					@foreach($target_fields as $field)
						<td>{{ $item[$field] }}</td>
					@endforeach
					@includeIf('layouts/default_action')
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	@includeIf('layouts/view_paginator')
	@hasSection('paginator')
		@yield('paginator')
	@endif
</div>

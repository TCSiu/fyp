<div class="card-body">
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				@foreach($target_fields as $field)
					<th>{{ __(ucwords(str_replace('_', ' ', $field))) }}</th>
				@endforeach
				<th>{{ __('actions') }}</th>
			</thead>
			<tbody>
				@foreach($data as $item)
					<tr>
					@foreach($target_fields as $field)
						<td>{{ $item[$field] }}</td>
					@endforeach
					@includeIf('common/action_button')
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	@includeIf('common/view_paginator')
	@hasSection('paginator')
		@yield('paginator')
	@endif
</div>
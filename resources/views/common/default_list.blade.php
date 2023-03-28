<div class="card-body">
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				@foreach($target_fields as $field => $header)
					<th class="text-nowrap">{{ __(ucwords(str_replace('_', ' ', $header))) }}</th>
				@endforeach
				<th class="text-nowrap">{{ __('Actions') }}</th>
			</thead>
			<tbody>
				@foreach($data as $item)
					<tr>
					@foreach($target_fields as $field => $header)
						<td class="text-nowrap">{{ $item[$field] }}</td>
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

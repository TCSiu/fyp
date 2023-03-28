@php
$actions = [
	'view' => [
		'icon' 	=>	'eye',
		'type'	=>	'normal',
	],
	'edit' => [
		'icon' 	=> 	'edit',
		'type'	=>	'normal',
	],
	'delete' => [
		'icon' 	=> 	'trash-2',
		'class'	=> 	'text-danger',
		'type' 	=>	'delete',
	],
	'assign' => [
		'icon'	=>	'check-square',
		'class'	=>	'text-success',
		'type'	=>	'assign',
	]
];
@endphp
<td class="text-nowrap">
	@isset($allow_actions)
		@foreach($actions as $action => $config)
			@if(in_array($action, $allow_actions))
			@isset($config['type'])
			@if($config['type'] == 'normal')
			<a href="{{ route('cms.'.$action, ['model' => $model, 'id' => $item->id]) }}" class="{{ $config['class'] ?? '' }}"><i class="align-middle" data-feather="{{ $config['icon'] ?? '' }}"></i></a>
			@elseif($config['type'] == 'delete')
			<a class="{{ $config['class'] ?? '' }}" id="{{ 'btn_is_delete_modal_' . $item->id }}" data-bs-toggle="modal" data-bs-target="#is_delete_modal">
                <i class="align-middle" data-feather="{{ $config['icon'] ?? '' }}"></i>
			</a>
            {{ View::make('panel/part/delete', ['id' => $item->id, 'model' => $model]) }}
			@elseif($config['type'] == 'assign')
			@if(is_null($item->relative_staff))
			<a class="{{ $config['class'] ?? '' }}" id="{{ 'btn_assign_modal_' . $item->id }}" data-bs-toggle="modal" data-bs-target="#assign_modal" data-bs-value="{{ $item->id }}">
                <i class="align-middle" data-feather="{{ $config['icon'] ?? '' }}"></i>
			</a>
			@endif
			{{ View::make('panel/part/assign', ['model' => $model, 'company_id' => $company_id]) }}
			@endif
			@endif
			@endif
		@endforeach
	@endif
</td>
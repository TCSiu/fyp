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
		'type' 	=>	'modal',
	],
];
@endphp
<td>
	@isset($allow_actions)
		@foreach($actions as $action => $config)
			@if(in_array($action, $allow_actions))
			@isset($config['type'])
			@if($config['type'] == 'normal')
			<a href="{{ route('cms.'.$action, ['model' => $model, 'id' => $item->id]) }}" class="text-nowrap {{ $config['class'] ?? '' }}"><i class="align-middle" data-feather="{{ $config['icon'] ?? '' }}"></i></a>
			@elseif($config['type'] == 'modal')
			<a class="text-nowrap {{ $config['class'] ?? '' }}" id="btn_is_delete_modal" data-bs-toggle="modal" data-bs-target="#is_delete_modal">
                <i class="align-middle" data-feather="{{ $config['icon'] ?? '' }}"></i>
			</a>
            {{ View::make('panel/part/modal', ['type' => 'delete', 'id' => $item->id, 'model' => $model]) }}
			@endif
			@endif
			@endif
		@endforeach
	@endif
</td>
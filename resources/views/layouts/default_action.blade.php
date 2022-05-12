@php
    $icon_map = '{
        "view" : "eye",
        "edit" : "edit",
        "delete" : "trash-2:,
    }';
@endphp
<td>
    @foreach($allow_actions as $action)
        <a href="{{ route('cms.'.$action, ['model' => $model, 'id' => $item->id]) }}" class="text-nowrap"><i class="align-middle me-2" data-feather="{{ $icon_map.$action }}"></i></a>
    @endforeach
</td>
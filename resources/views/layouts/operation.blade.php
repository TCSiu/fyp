@isset($operations)
@foreach($operations as $operation)
@if(strcmp($operation, 'create') == 0)
<div class="mt-3">
    <a class="btn btn-success white-space-nowrap" href="{{ route('cms.create', ['model' => $model]) }}" id="btn-create">
        <i class="align-middle me-2" data-feather="plus"></i>{{ __('Create New') }}
    </a>
</div>
@endif
@if(strcmp($operation, 'gen_csv') == 0)
<div class="mt-3">
    <a class="btn btn-secondary white-space-nowrap" href="{{ route('cms.get_csv', ['model' => $model]) }}" id="btn-create">
        <i class="align-middle me-2" data-feather="plus"></i>{{ __('Generate CSV') }}
    </a>
</div>
@endif
@endforeach
@endif
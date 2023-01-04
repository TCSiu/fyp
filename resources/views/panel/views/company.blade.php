@php

@endphp

<div class="card-body">
    <div class="row">
        <div class="col-10">
            @foreach($fields as $field => $format)
                @if(Str::is('normal', $format))
                <div class="fs-4 card-text"> {{ __(ucwords(str_replace('_', ' ', $field)) . ': ' . $data[$field]) }}</div>
                @elseif(Str::is('special.*', $format))
                <div class="fs-4 card-text"> {{ __(ucwords(str_replace('_', ' ', substr($format, strpos($format, ".") + 1))) . ': ' . $data[$field]) }}</div>
                @elseif(Str::is('boolean', $format))
                <div class="fs-4 card-text"> {{ __(ucwords(str_replace('_', ' ', $field)) . ': ' . ($data[$field]?'true':'false')) }}</div>
                @endif
            @endforeach
        </div>
        <div class="col-2">
            <div class="fs-4 card-text">
                <a class="btn btn-primary float-end me-2" href="{{ route('cms.edit', ['model' => $model, 'id' => $id]) }}" role="button">
                    <i class="align-middle me-2" data-feather="edit"></i>{{ __('Edit') }}
                </a>
            </div>
        </div>
    </div>
</div>


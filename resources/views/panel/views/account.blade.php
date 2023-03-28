<div class="card-body">
    <div class="row">
        <div class="col-10">
            <label for="profile_icon" class="card-text fs-4 form-label">{{ __('Profile Picture:') }}</label><br />
            <img src="{{ $data->getUserProfilePicture() }}" id="profile_icon" class="rounded img-thumbnail" alt="Profile Icon" style="width:150px;height:auto;" />
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
    @if(!Str::is('admin', $data['type']))
    <div class="row">
        <div class="card-footer">
            <a class="btn btn-secondary me-2" href="{{ route('cms.list', ['model' => $model]) }}" role="button">
                <i class="align-middle me-2" data-feather="corner-down-right"></i>{{ __('Back') }}
            </a>
            <a class="btn btn-success me-2" href="{{ route('cms.create', ['model' => $model]) }}" role="button">
                <i class="align-middle me-2" data-feather="plus"></i>{{ __('Create New') }}
            </a>
        </div>
    </div>
    @endif
</div>


<div class="card-body">
    @foreach($fields as $field => $format)
        @if(Str::is('normal', $format))
        <div class="fs-4 card-text"> {{ __(isset($data[$field]) ? ucwords(str_replace('_', ' ', $field)) . ': ' . $data[$field] : ucwords(str_replace('_', ' ', $field)) . ': ') }}</div>
        @elseif(Str::is('special.*', $format))
        <div class="fs-4 card-text"> {{ __(ucwords(str_replace('_', ' ', substr($format, strpos($format, ".") + 1))) . ': ' . $data[$field]) }}</div>
        @elseif(Str::is('table', $format))
        <table class="table table-striped table-hover" id="order_table">
            <thead>
                <th>#</th>
                <th class="col-6 col-md-7">Product Name</th>
                <th>Number of Product(s)</th>
            </thead>
            <tbody id="order_tbody">
                @php
                    $row_number = 1;
                    $table_data = json_decode($data[$field], true);
                @endphp
                @foreach($table_data as $key => $value)
                <tr>
                    <td>
                        <span>{{ $row_number }}</span>
                    </td>
                    <td>
                        <span>{{ $value['product_name'] }}</span>
                    </td>
                    <td>
                        <span>{{ $value['product_number'] }}</span>
                    </td>
                </tr>
                @php
                    $row_number++;
                @endphp
                @endforeach
            </tbody>	
        </table>
        @elseif(Str::is('boolean', $format))
        <div class="fs-4 card-text"> {{ __(ucwords(str_replace('_', ' ', $field)) . ': ' . ($data[$field]?'true':'false')) }}</div>
        @endif
    @endforeach
    <div class="row">
        <div class="card-footer">
            <a class="btn btn-secondary me-2" href="{{ route('cms.list', ['model' => $model]) }}" role="button">
                <i class="align-middle me-2" data-feather="corner-down-right"></i>{{ __('Back') }}
            </a>
            <a class="btn btn-primary me-2" href="{{ route('cms.edit', ['model' => $model, 'id' => $id]) }}" role="button">
                <i class="align-middle me-2" data-feather="edit-3"></i>{{ __('Edit') }}
            </a>
            <a class="btn btn-success me-2" href="{{ route('cms.create', ['model' => $model]) }}" role="button">
                <i class="align-middle me-2" data-feather="plus"></i>{{ __('Create New') }}
            </a>
            <button type="button" class="btn btn-danger me-2" id="btn_is_delete_modal" data-bs-toggle="modal" data-bs-target="#is_delete_modal">
                <i class="align-middle me-2" data-feather="trash-2"></i>{{ __('Delete') }}
            </button>
            {{ View::make('panel/part/delete', ['id' => $id, 'model' => $model]) }}
        </div>
    </div>
</div>


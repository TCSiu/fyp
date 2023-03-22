@php

@endphp

<div class="card-body">
    <div class="row">
        <div class="col-12">
            @foreach($fields as $field => $format)
                @if(Str::is('normal', $format))
                <div class="fs-4 card-text"> {{ __(ucwords(str_replace('_', ' ', $field)) . ': ' . $data[$field]) }}</div>
                @elseif(Str::is('special.*', $format))
                <div class="fs-4 card-text"> {{ __(ucwords(str_replace('_', ' ', substr($format, strpos($format, ".") + 1))) . ': ' . $data[$field]) }}</div>
                @elseif(Str::is('boolean', $format))
                <div class="fs-4 card-text"> {{ __(ucwords(str_replace('_', ' ', $field)) . ': ' . ($data[$field]?'true':'false')) }}</div>
                @elseif(Str::is('table_json.*', $format))
                <div class="fs-4 card-text"> {{ __(ucwords(str_replace('_', ' ', $field)) .':' ) }}</div>
                @php
                    $header = explode('/', substr($format, strpos($format, ".") + 1));
                @endphp
                <table class="table table-hover" id="{{ $field . '_table' }}">
                    <thead>
                        <th scope="col">#</th>
                        @foreach($header as $header_value)
                        <th scope="col">{{ __(ucwords(str_replace('_', ' ', $header_value))) }}</th>
                        @endforeach
                    </thead>
                    <tbody id="{{ $field . '_tbody'}}">
                        @php
                            $row_number = 1;
                            $table_data = $data[$field];
                        @endphp
                        <tr scope="row">
                            <td>
                                <span>{{ $row_number }}</span>
                            </td>
                            @foreach($header as $header_value)
                            @if(array_key_exists($header_value, $table_data))
                            <td>
                                <span>{{ $table_data[$header_value] }}</span>
                            </td>
                            @endif
                            @endforeach
                        </tr>
                    </tbody>	
                </table>
                @elseif(Str::is('table.*', $format))
                <div class="fs-4 card-text"> {{ __(ucwords(str_replace('_', ' ', $field)) .':' ) }}</div>
                @php
                    $header = explode('/', substr($format, strpos($format, ".") + 1));
                @endphp
                <table class="table table-striped table-hover" id="{{ $field . '_table' }}">
                    <thead>
                        <th scope="col">#</th>
                        @foreach($header as $header_value)
                        <th scope="col">{{ __(ucwords(str_replace('_', ' ', $header_value))) }}</th>
                        @endforeach
                    </thead>
                    <tbody id="{{ $field . '_tbody'}}">
                        @php
                            $row_number = 1;
                            $table_data = $data[$field];
                        @endphp
                        @foreach($table_data as $key => $value)
                        <tr scope="row">
                            <td>
                                <span>{{ $row_number }}</span>
                            </td>
                            @foreach($header as $header_value)
                            <td>
                                <span>{{ $value[$header_value] }}</span>
                            </td>
                            @endforeach
                        </tr>
                        @php
                            $row_number++;
                        @endphp
                        @endforeach
                    </tbody>	
                </table>
                @endif
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="card-footer">
            <a class="btn btn-secondary me-2" href="{{ route('cms.list', ['model' => $model]) }}" role="button">
                <i class="align-middle me-2" data-feather="corner-down-right"></i>{{ __('Back') }}
            </a>
        </div>
    </div>
</div>


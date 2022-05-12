<div class="card-body">
    <table class="table table-bordered">
        <thead>
            @foreach($target_fields as $field)
                <th>{{ $field }}</th>
            @endforeach
            <th>{{ __('actions') }}</th>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                @foreach($target_fields as $field)
                    <td>{{ $item[$field] }}</td>
                @endforeach
                @includeIf('layouts/default_action')
                </tr>
            @endforeach
        </tbody>
    </table>        
</div>

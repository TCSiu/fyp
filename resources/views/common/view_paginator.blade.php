@section('paginator')
@php
$current_page = $data->currentPage();
$end_page = $data->lastPage();
$page_to_gen = $data->getUrlRange(1, $end_page);
$count = sizeof($page_to_gen);
$paginator = ['1', $current_page - 1, $current_page, $current_page + 1, $end_page];
@endphp
<nav class="mt-3" aria-label="Page navigation example">
    <ul class="pagination m-0">
        <li @class(['page-item', 'disabled' => $data->onFirstPage()])>
            <a class="page-link" href="{{ route('cms.list', ['model' => $model, 'page' => $current_page - 1]) }}" tabindex="-1" aria-disabled="{{ $data->onFirstPage()?'true':'false' }}">Previous</a>
        </li>
        @foreach($page_to_gen as $key => $value)
            @if(in_array($key, $paginator))
            <li @class(['page-item', 'active' => $key == $current_page])>
                <a class="page-link" href="{{ $value }}">{{ $key }}</a>
            </li>
            @endif
        @endforeach
        <li @class(['page-item', 'disabled' => ($data->currentPage() == $data->lastPage())])>
            <a class="page-link" href="{{ route('cms.list', ['model' => $model, 'page' => $current_page + 1]) }}" tabindex="-1" aria-disabled="{{ $data->currentPage() == $data->lastPage()?'true':'false' }}">Next</a>
        </li>
    </ul>
</nav>
@stop
@section('paginator')
@php
$current_page = $data->currentPage();
$end_page = $data->lastPage();
$page_to_gen = $data->getUrlRange(1, $end_page);
$count = sizeOf($page_to_gen);
$paginator = ['1', $current_page - 1, $current_page, $current_page + 1, $end_page];

@endphp
<nav aria-label="Page navigation example">
    <ul class="pagination">
        <li class="page-item {{ $data->onFirstPage()?'disabled':'' }}"><a class="page-link" href="{{ route('cms.list', ['model' => $model, 'page' => $current_page - 1]) }}" tabindex="-1" aria-disabled="{{ $data->onFirstPage()?'true':'false' }}">Previous</a></li>
        @foreach($page_to_gen as $key => $value)
        @if(in_array($key, $paginator))
        <li class="page-item {{ $key == $current_page?'active':'' }}"><a class="page-link" href="{{ $value }}">{{ $key }}</a></li>
        @endif
        @endforeach
        <li class="page-item {{ ($data->currentPage() == $data->lastPage())?'disabled':'' }}"><a class="page-link" href="{{ route('cms.list', ['model' => $model, 'page' => $current_page + 1]) }}" tabindex="-1" aria-disabled="{{ $data->currentPage() == $data->lastPage()?'true':'false' }}">Next</a></li>
    </ul>
</nav>
@stop
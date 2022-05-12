@php
if(Cookie::has('access_token')){
    $token = Cookie::get('access_token');
}
if(Cookie::has('auth_user')){
    $cookie_user = Cookie::get('auth_user');
    $auth_user = json_decode($cookie_user, true);
}
@endphp
@extends('layouts/default')

@section('content')
<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">{{ __('Content Management System') }}</h1>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ __($inpage_title) }}</h5>
                        @if(isset($data))
                            <h6 class="card-subtitle text-muted">
                                @if($total_count == 0)
                                    {{ __('There is no records yet.') }}
                                @else
                                    {{ __('Showing :count of :total records.', ['count' => sizeof($data), 'total' => intval($total_count)]) }}
                                @endif
                            </h6>
                            <div class="mt-3">
								<a class="btn btn-success white-space-nowrap" href="{{ route('cms.create', ['model' => $model]) }}" id="btn-create">
									<i class="align-middle me-2" data-feather="plus"></i>{{ __('Create New') }}
								</a>
							</div>
                        @else
                            <h6 class="card-subtitle text-muted">{{ __('An error has occured!') }}</h6>
                        @endif
                    </div>
                    @includeIf('layouts/default_list_page')
                </div>
            </div>
        </div>
    </div>
</main>
@stop
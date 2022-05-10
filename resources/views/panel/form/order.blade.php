@php
if(Cookie::has('access_token')){
    $token = Cookie::get('access_token');
}
if(Cookie::has('auth_user')){
    $data = Cookie::get('auth_user');
    $auth_user = json_decode($data, true);
}
@endphp
@extends('layouts/default')

@section('content')
<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">{{ __('Create Order') }}</h1>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('Empty card') }}</h5>
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@stop
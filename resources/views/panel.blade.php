@php
if(Cookie::has('access_token')){
    $token = Cookie::get('access_token');
}
@endphp
@extends('layouts/plain')

@section('content')
    {{ $token }}
    <a href="{{ route('logout') }}">Logout</a>
@stop
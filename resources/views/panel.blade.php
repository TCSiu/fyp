@php
if(session()->has('access_token')){
    $token = session()->get('access_token');
}
@endphp
@extends('layouts/plain')

@section('content')
    {{ $token }}
@stop
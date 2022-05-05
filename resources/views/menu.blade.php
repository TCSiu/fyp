@php
if(isset($_SESSION['access_token'])){
    $token = session()->get('access_token');
}
@endphp
@extends('layouts/plain')

@section('content')
    {{ $access_token }}
@stop
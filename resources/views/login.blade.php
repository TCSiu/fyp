@php
$title = 'Login Page';
@endphp
@extends('common/plain')

@section('content')
<div class="container-fluid vh-100 bg-primary position-relative">
	@isset($errors)
	{{ View::make('part/alert', ['errors' => $errors]) }}
	@endif
	<div class="card d-block w-25 position-absolute top-50 start-50 translate-middle">
		<div class="card-header text-center">
			<h4>{{ __('Login') }}</h1>	
		</div>
		<div class="card-body">
			<form action="{{ route('login')}}" method="post" autocomplete="off" enctype="multipart/form-data">
				@csrf
				<div class="row mb-3">
					<div class="col-12">
						<label for="l_username" class="form-label">{{ __('Username') }}</label>
						<input type="text" class="form-control" name="username" id="l_username" value="{{ old('username') ?? '' }}" placeholder="Enter a Username"/>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-12">
						<label for="l_password" class="form-label">{{ __('Password') }}</label>
						<input type="password" class="form-control" name="password" id="l_password" placeholder="Enter a Password"/>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<input class="btn btn-primary w-25 float-end rounded-pill" type="submit" value="Login">
						<input class="btn btn-light w-25 float-end rounded-pill" type="reset" value="Reset">
					</div>
				</div>
				<div class="row">
					<div class="col-12"><hr></hr></div>
					<div class="col-12">
						<a href="{{ route('register') }}">{{ __('Register an Account') }}</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@stop
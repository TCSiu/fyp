@php
$title = 'Register Page';
@endphp
@extends('layouts/plain')

@section('content')
<div class="container-fluid vh-100 bg-primary position-relative">
	@isset($errors)
	{{ View::make('part/alert', ['errors' => $errors]) }}
	@endif
	<div class="card d-block w-50 position-absolute top-50 start-50 translate-middle">
		<div class="card-header text-center">
			<h4>{{ __('Register') }}</h1>	
		</div>
		<div class="card-body">
			<form action="{{ route('register') }}" method="post">
				@csrf
				<div class="row mb-3">
					<div class="col-6">
						<label for="registerUserName" class="form-label">{{ __('Username') }}</label>
						<input type="text" class="form-control" name="name" id="registerUserName" placeholder="Enter a Username"/>
					</div>
					<div class="col-6">
						<label for="registerEmail" class="form-label">{{ __('Email') }}</label>
						<input type="email" class="form-control" name="email" id="registerEmail" placeholder="Enter a Email"/>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-12">
						<label for="registerPassword" class="form-label">{{ __('Password') }}</label>
						<input type="password" class="form-control" name="password" id="registerPassword" placeholder="Enter a Password"/>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-12">
						<label for="registerPasswordConfirmation" class="form-label">{{ __('Password Confirm') }}</label>
						<input type="password" class="form-control" name="password_confirmation" id="registerPasswordConfirmation" placeholder="Re-enter a Password"/>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-12">
						<input class="btn btn-primary w-25 me-1" type="submit" value="Register">
						<input class="btn btn-light w-25 me-1" type="reset" value="Reset">
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<a href="{{ route('login') }}">{{ __('Login an Account') }}</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@stop

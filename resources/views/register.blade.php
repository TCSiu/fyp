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
		<div class="card-header text-center"><h4>{{ __('Register') }}</h1></div>
		<div class="card-body">
			<div class="steps-register">
				<div class="steps-row setup-panel">
					<div class="steps-register-step">
						<a href="#step-company-info" type="button" class="btn btn-success rounded-circle">1</a>
						<p>{{ __('Company') }}</p>
					</div>
					<div class="steps-register-step">
						<a href="#step-admin" type="button" class="btn btn-primary rounded-circle" disabled="disabled">2</a>
						<p>{{ __('Account') }}</p>
					</div>
				</div>
			</div>
			<form action="{{ route('register') }}" method="post">
				@csrf
				<div class="row setup-content" id="step-company-info">
					<div class="col-12">
						<div class="row mb-3">
							<div class="col-4">
								<label for="r_c_company_name" class="form-label">{{ __('Company Name') }}</label>
								<input type="text" class="form-control" name="company_name" id="r_c_company_name" value="{{ old('company_name') ?? '' }}" placeholder="Enter a Company Name" />
							</div>
							<div class="col-4">
								<label for="r_c_email" class="form-label">{{ __('Office Email') }}</label>
								<input type="email" class="form-control" name="office_email" id="r_c_email" value="{{ old('office_email') ?? '' }}" placeholder="Enter a Office Email" />
							</div>
							<div class="col-4">
								<label for="r_c_office_phone" class="form-label">{{ __('Office Phone Number') }}</label>
								<input type="text" class="form-control" name="office_phone" id="r_c_office_phone" value="{{ old('office_phone') ?? '' }}" placeholder="Enter a Office Phone Number" />
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-12">
								<label for="r_c_office_address" class="form-label">{{ __('Office Address') }}</label>
								<input type="text" class="form-control" name="office_address" id="r_c_office_address" value="{{ old('office_address') ?? '' }}" placeholder="Enter Your Office Address" />
							</div>
						</div>
						<div class="row mb-3">
							<label for="autocomplete" class="form-label form-required">{{ __('Warehouse Address:') }}{!! Utility::required() !!}</label>
							<div class="input-group">
								<input type="text" class="form-control" aria-describedby="autocomplete" role="presentation" name="warehouse_address1" id="autocomplete" value="{{ old('warehouse_address1') ?? '' }}" placeholder="Enter Your Warehouse Address" />
								<input type="number" step="0.00001" min="0" max="400" class="form-control text-center" placeholder="lat" readonly name="lat" value="{{ old('lat') ?? '' }}" style="max-width:160px;" />
								<input type="number" step="0.00001" min="0" max="400" class="form-control text-center" placeholder="lng" readonly name="lng" value="{{ old('lng') ?? '' }}" style="max-width:160px;" />
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-12">
								<label for="r_c_warehouse_address2" class="form-label">{{ __('Apartment, unit, suite, or floor #:') }}</label>
								<input type="text" class="form-control" aria-describedby="r_c_warehouse_address2" role="presentation" name="warehouse_address2" id="r_c_warehouse_address2" value="{{ old('warehouse_address2') ?? '' }}" />
							</div>
						</div>
						<div class="row">
							<div class="col-12 text-danger"><strong>{!! Utility::required() !!} {{ __('required') }}</strong></div>
						</div>
						<button class="btn btn-primary rounded-pill nextBtn float-end" type="button">{{ __('Next') }}</button>
					</div>
				</div>
				<div class="row setup-content" id="step-admin">
					<div class="col-12">
						<div class="row mb-3">
							<div class="col-6">
								<label for="r_a_first_name" class="form-label">{{ __('First Name') }}</label>
								<input type="text" class="form-control" name="first_name" id="r_a_first_name" value="{{ old('first_name') ?? '' }}" placeholder="Enter Your First Name" />
							</div>
							<div class="col-6">
								<label for="r_a_last_name" class="form-label">{{ __('Last Name') }}</label>
								<input type="text" class="form-control" name="last_name" id="r_a_last_name" value="{{ old('last_name') ?? '' }}" placeholder="Enter Your Last Name" />
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-4">
								<label for="r_a_username" class="form-label">{{ __('Username') }}</label>
								<input type="text" class="form-control" name="username" id="r_a_username" value="{{ old('username') ?? '' }}" placeholder="Enter a Username" />
							</div>
							<div class="col-4">
								<label for="r_a_email" class="form-label">{{ __('Email') }}</label>
								<input type="email" class="form-control" name="email" id="r_a_email" value="{{ old('email') ?? '' }}" placeholder="Enter a Email" />
							</div>
							<div class="col-4">
								<label for="r_a_phone" class="form-label">{{ __('Phone') }}</label>
								<input type="text" class="form-control" name="phone" id="r_a_phone" value="{{ old('phone') ?? '' }}" placeholder="Enter a Phone" />
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-12">
								<label for="r_a_password" class="form-label">{{ __('Password') }}</label>
								<input type="password" class="form-control" name="password" id="r_a_password" placeholder="Enter a Password" />
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-12">
								<label for="r_a_password_confirm" class="form-label">{{ __('Password Confirm') }}</label>
								<input type="password" class="form-control" name="password_confirmation" id="r_a_password_confirm" placeholder="Re-enter a Password" />
							</div>
						</div>
						<div class="row">
							<div class="col-12 text-danger"><strong>{!! Utility::required() !!} {{ __('required') }}</strong></div>
						</div>
						<button type="submit" class="btn btn-success rounded-pill float-end">{{ __('Submit') }}</button>
						<button type="button" class="btn btn-secondary rounded-pill prevBtn float-start">{{ __('Previous') }}</button>
					</div>
				</div>
				<div class="row">
					<div class="col-12"><hr></hr></div>
					<div class="col-12">
						<a href="{{ route('login') }}" class="">{{ __('Login an Account') }}</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@push('scripts')
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function(){
    var stepBtn = document.querySelectorAll('div.setup-panel div a');
    var allContent = document.querySelectorAll('.setup-content');
    var nextBtn = document.querySelectorAll('.nextBtn');
    var prevBtn = document.querySelectorAll('.prevBtn');

    allContent.forEach((e) => {
        e.style.display = 'none';
    });

    stepBtn.forEach(function(e){
        e.addEventListener('click', function(event){
            event.preventDefault();
            var target = e.getAttribute('href');
            var targetContent = document.querySelector(target);
            var index = [...stepBtn].indexOf(e);
            stepBtn.forEach((btn) => {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-primary');
            });
            for(var i = 0; i < index + 1; i++){
                stepBtn[i].classList.add('btn-success');
            }
            allContent.forEach((content) => {
                content.style.display = 'none';
            });
            targetContent.style.display = 'flex';
            targetContent.querySelectorAll('input')[0].focus();
        });
    });

    nextBtn.forEach(function(e, index){
        e.addEventListener('click', function(){
            stepBtn[index+1].click();
        });
    });

    prevBtn.forEach(function(e, index){
        e.addEventListener('click', function(){
            var len = stepBtn.length - index - 1;
            stepBtn[len - 1].click();
        });
    });

    document.querySelector('div.setup-panel div a.btn-success').click();
});
</script>
{{ View::make('layouts/google_map', ['type' => 'autocomplete']) }}
@endpush
<!-- @stack("scripts") -->
@stop

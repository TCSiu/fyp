@php
$account = auth()->user();
@endphp
<div class="card-body">
	<form action="{{ route('cms.store', ['model' => $model, 'id' => (isset($id)?intval($id):false)]) }}" method="POST">
		@method('PUT')
		@csrf
		<div class="col-12">
			<div class="row mb-3">
				<div class="col-3">
					<label for="profile_icon" class="form-label">{{ __('Profile Icon Preview') }}</label>
					<img src="{{ secure_asset('img/default icon.jpg') }}" id="profile_icon" class="rounded" alt="Profile Icon" style="height:200px;width:200px" />
				</div>
				<div class="col">
					@includeIf('panel/part/imageUpload')
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-6">
					<label for="r_a_first_name" class="form-label">{{ __('First Name') }}</label>
					<input type="text" class="form-control" name="first_name" id="first_name" value="{{ $record['first_name'] ?? old('first_name') ?? '' }}" placeholder="Enter Your First Name" />
				</div>
				<div class="col-6">
					<label for="r_a_last_name" class="form-label">{{ __('Last Name') }}</label>
					<input type="text" class="form-control" name="last_name" id="last_name" value="{{ $record['last_name'] ?? old('last_name') ?? '' }}" placeholder="Enter Your Last Name" />
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-4">
					<label for="r_a_username" class="form-label">{{ __('Username') }}</label>
					<input type="text" class="form-control" name="username" id="username" value="{{ $record['username'] ?? old('username') ?? '' }}" placeholder="Enter a Username" />
				</div>
				<div class="col-4">
					<label for="r_a_email" class="form-label">{{ __('Email') }}</label>
					<input type="email" class="form-control" name="email" id="email" value="{{ $record['email'] ?? old('email') ?? '' }}" placeholder="Enter a Email" />
				</div>
				<div class="col-4">
					<label for="r_a_phone" class="form-label">{{ __('Phone') }}</label>
					<input type="text" class="form-control" name="phone" id="phone" value="{{ $record['phone'] ?? old('phone') ?? '' }}" placeholder="Enter a Phone" />
				</div>
			</div>
			<!-- <div class="row mb-3">
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
			</div> -->
			<div class="row">
				<div class="col-12 text-danger"><strong>{!! Utility::required() !!} {{ __('required') }}</strong></div>
			</div>
			<div class="row mb-3">
				<div class="col">
					<button type="submit" class="btn btn-success me-2" id="orderFormSubmit">
						<i class="align-middle" data-feather="save"></i> {{ __('Save') }}
					</button>
					<a href="{{ route('cms.view', ['model' => 'account', 'id' => $account->id]) }}" class="btn btn-secondary me-2">
						<i class="align-middle" data-feather="x"></i> {{ __('Cancel') }}
					</a>
				</div>
			</div>
		</div>
	</form>
</div>
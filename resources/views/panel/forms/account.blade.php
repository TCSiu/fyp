@php
$account = auth()->user();
if(isset($images)){
	if(is_array($images)){
		if(sizeOf($images) > 0){

		}
    }else{
        $image = $images;
    }
}
@endphp
<div class="card-body">
	<form action="{{ route('cms.store', ['model' => $model, 'id' => (isset($id)?intval($id):false)]) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
		@method('PUT')
		@csrf
		<div class="col-12">
			<div class="row mb-3">
				<div class="col-12 col-md-3 mb-2">
					<label for="profile_icon" class="form-label">{{ __('Profile Icon Preview') }}</label><br />
					<img src="{{ isset($image) ? secure_asset($image) : secure_asset('img/default icon.jpg') }}" id="profile_icon" class="rounded img-thumbnail" alt="Profile Icon" style="width:150px;height:auto;" />
				</div>
				<div class="col-12 col-md-3">
					@includeIf('panel/part/imageUpload')
				</div>
				<div class="col-12 col-md-6">
					<label for="sex" class="form-label">Gender:</label>
					<select class="form-select form-select-md mb-3" aria-label=".form-select-md" name="sex" id="sex">
						<option selected disabled>Select Gender</option>
						<option value="male"{{ ($record['sex'] ?? old('sex')) == 'male' ? 'selected' : '' }}>Male</option>
						<option value="female" {{ ($record['sex'] ?? old('sex')) == 'female' ? 'selected' : '' }}>Female</option>
						<option value="x" {{ ($record['sex'] ?? old('sex')) == 'x' ? 'selected' : '' }}>Gender X</option>
					</select>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-12 col-md-6 mb-2">
					<label for="r_a_first_name" class="form-label">{{ __('First Name') }}{!! Utility::required() !!}</label>
					<input type="text" class="form-control" name="first_name" id="first_name" value="{{ $record['first_name'] ?? old('first_name') ?? '' }}" placeholder="Enter Your First Name" />
				</div>
				<div class="col-12 col-md-6 mb-2">
					<label for="r_a_last_name" class="form-label">{{ __('Last Name') }}{!! Utility::required() !!}</label>
					<input type="text" class="form-control" name="last_name" id="last_name" value="{{ $record['last_name'] ?? old('last_name') ?? '' }}" placeholder="Enter Your Last Name" />
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-12 col-md-4 mb-2">
					<label for="r_a_username" class="form-label">{{ __('Username') }}{!! Utility::required() !!}</label>
					<input type="text" class="form-control" name="username" id="username" value="{{ $record['username'] ?? old('username') ?? '' }}" placeholder="Enter a Username" />
				</div>
				<div class="col-12 col-md-4 mb-2">
					<label for="r_a_email" class="form-label">{{ __('Email') }}{!! Utility::required() !!}</label>
					<input type="email" class="form-control" name="email" id="email" value="{{ $record['email'] ?? old('email') ?? '' }}" placeholder="Enter a Email" />
				</div>
				<div class="col-12 col-md-4 mb-2">
					<label for="r_a_phone" class="form-label">{{ __('Phone') }}{!! Utility::required() !!}</label>
					<input type="text" class="form-control" name="phone" id="phone" value="{{ $record['phone'] ?? old('phone') ?? '' }}" placeholder="Enter a Phone" />
				</div>
			</div>
			@if(isset($isCreate) && is_bool($isCreate) && $isCreate)
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
			@endif
			<div class="row">
				<div class="col-12 text-danger"><strong>{!! Utility::required() !!} {{ __('required') }}</strong></div>
			</div>
			<div class="row mb-3">
				<div class="col">
					<button type="submit" class="btn btn-success me-2" id="orderFormSubmit">
						<i class="align-middle" data-feather="save"></i> {{ __('Save') }}
					</button>
					@isset($id)
					<a href="{{ route('cms.view', ['model' => $model, 'id' => $id]) }}" class="btn btn-secondary me-2">
						<i class="align-middle" data-feather="x"></i> {{ __('Cancel') }}
					</a>
					@else
					<a href="{{ route('cms.list', ['model' => $model]) }}" class="btn btn-secondary me-2">
						<i class="align-middle" data-feather="x"></i> {{ __('Cancel') }}
					</a>
					@endisset
				</div>
			</div>
		</div>
	</form>
</div>
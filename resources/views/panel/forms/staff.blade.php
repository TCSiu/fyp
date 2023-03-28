<div class="card-body">
	<form action="{{ route('cms.store', ['model' => $model, 'id' => (isset($id)?intval($id):false)]) }}" method="POST">
		@method('PUT')
		@csrf
		<div class="row">
			<div class="col-12 col-md-2 mb-2">
				<label for="staffSex" class="form-label">Gender:</label>
				<select class="form-select form-select-md" aria-label=".form-select-md" name="sex" id="staffSex">
					<option selected disabled>Select Gender</option>
					<option value="male" @selected(old('sex', $record['sex'] ?? '') == 'male')>Male</option>
					<option value="female" @selected(old('sex', $record['sex'] ?? '') == 'female')>Female</option>
					<option value="x" @selected(old('sex', $record['sex'] ?? '') == 'x')>Gender X</option>
				</select>
			</div>
			<div class="col-12 col-md-5 mb-2">
				<label for="staffFirstName" class="form-label">First Name:</label>
				<input type="text" class="form-control" aria-describedby="staffFirstName" name="first_name" id="staffFirstName" value="{{ $record['first_name'] ?? old('first_name') ?? '' }}" />
			</div>
			<div class="col-12 col-md-5 mb-2">
				<label for="staffLastName" class="form-label">Last Name:</label>
				<input type="text" class="form-control" aria-describedby="staffLastName" name="last_name" id="staffLastName" value="{{ $record['last_name'] ?? old('last_name') ?? '' }}" />
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-4 mb-2">
				<label for="staffPhoneNumber" class="form-label">Phone Number:</label>
				<input type="tel" class="form-control" aria-describedby="staffPhoneNumber" name="phone_number" id="staffPhoneNumber" value="{{ $record['phone_number'] ?? old('phone_number') ?? '' }}" />
			</div>
			<div class="col-12 col-md-8 mb-2">
				<label for="staffEmail" class="form-label">Email:</label>
				<input type="email" class="form-control" aria-describedby="staffEmail" name="email" id="staffEmail" value="{{ $record['email'] ?? old('email') ?? '' }}" />
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-xl-7 mb-2">
				<label for="staffAcName" class="form-label">Account Name:</label>
				<input type="text" class="form-control" aria-describedby="staffAcName" name="ac_name" id="staffAcName" value="{{ $record['ac_name'] ?? old('ac_name') ?? '' }}" />
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-6 mb-2">
				<label for="staffPassword" class="form-label">Password:</label>
				<input type="password" class="form-control" aria-describedby="staffPassword" name="password" id="staffPassword" />
				<div id="staffPasswordHelp" class="form-text"><b>At least 1 Capital Letter, 1 Lower Letter and 1 Number!</b></div>
			</div>
			<div class="col-12 col-md-6 mb-2">
				<label for="staffPasswordConfirmation" class="form-label">Password Confirmation:</label>
				<input type="password" class="form-control" aria-describedby="staffPasswordConfirmation" name="password_confirmation" id="staffPasswordConfirmation" />
			</div>
		</div>
		<div class="row">
			<div class="col">
				<button type="submit" class="btn btn-primary me-2">
					<i class="align-middle" data-feather="save"></i> {{ __('Save') }}
				</button>
				<button type="reset" class="btn btn-secondary me-2">
					<i class="align-middle" data-feather="rotate-ccw"></i> {{ __('Reset') }}
				</button>
				<button type="{{ route('cms.list', ['model' => $model]) }}" class="btn btn-secondary me-2">
					<i class="align-middle" data-feather="corner-down-right"></i> {{ __('Cancel') }}
				</button>
			</div>
		</div>
	</form>
</div>
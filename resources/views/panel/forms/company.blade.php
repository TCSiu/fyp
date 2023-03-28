<div class="card-body">
	<form action="{{ route('cms.store', ['model' => $model, 'id' => (isset($id)?intval($id):false)]) }}" method="POST">
		@method('PUT')
		@csrf
		<div class="col-12">
			<div class="row mb-3">
				<div class="col-4">
					<label for="r_c_company_name" class="form-label">{{ __('Company Name') }}</label>
					<input type="text" class="form-control" name="company_name" id="r_c_company_name" value="{{ $record['company_name'] ?? old('company_name') ?? '' }}" placeholder="Enter a Company Name" />
				</div>
				<div class="col-4">
					<label for="r_c_email" class="form-label">{{ __('Office Email') }}</label>
					<input type="email" class="form-control" name="office_email" id="r_c_email" value="{{ $record['office_email'] ?? old('office_email') ?? '' }}" placeholder="Enter a Office Email" />
				</div>
				<div class="col-4">
					<label for="r_c_office_phone" class="form-label">{{ __('Office Phone Number') }}</label>
					<input type="text" class="form-control" name="office_phone" id="r_c_office_phone" value="{{ $record['office_phone'] ?? old('office_phone') ?? '' }}" placeholder="Enter a Office Phone Number" />
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-12">
					<label for="r_c_office_address" class="form-label">{{ __('Office Address') }}</label>
					<input type="text" class="form-control" name="office_address" id="r_c_office_address" value="{{ $record['office_address'] ?? old('office_address') ?? '' }}" placeholder="Enter Your Office Address" />
				</div>
			</div>
			<div class="row mb-3">
				<label for="autocomplete" class="form-label form-required">{{ __('Warehouse Address:') }}{!! Utility::required() !!}</label>
				<div class="input-group">
					<input type="text" class="form-control" aria-describedby="autocomplete" role="presentation" name="warehouse_address1" id="autocomplete" value="{{ $record['warehouse_address1'] ?? old('warehouse_address1') ?? '' }}" placeholder="Enter Your Warehouse Address" />
					<input type="number" step="0.00001" min="0" max="400" class="form-control text-center" placeholder="lat" readonly name="lat" value="{{ $record['lat'] ?? old('lat') ?? '' }}" style="max-width:160px;" />
					<input type="number" step="0.00001" min="0" max="400" class="form-control text-center" placeholder="lng" readonly name="lng" value="{{ $record['lng'] ?? old('lng') ?? '' }}" style="max-width:160px;" />
				</div>
				<h6 class="text-secondary mt-1">Select the deliver address from the drop-down list</h6>
			</div>
			<div class="row mb-3">
				<div class="col-12">
					<label for="r_c_warehouse_address2" class="form-label">{{ __('Apartment, unit, suite, or floor #:') }}</label>
					<input type="text" class="form-control" aria-describedby="r_c_warehouse_address2" role="presentation" name="warehouse_address2" id="r_c_warehouse_address2" value="{{ $record['warehouse_address2'] ?? old('warehouse_address2') ?? '' }}" />
				</div>
			</div>
			<div class="row">
				<div class="col-12 text-danger"><strong>{!! Utility::required() !!} {{ __('required') }}</strong></div>
			</div>
			<div class="row mb-3">
				<div class="col">
					<button type="submit" class="btn btn-success me-2">
						<i class="align-middle" data-feather="save"></i> {{ __('Save') }}
					</button>
					<button type="reset" class="btn btn-secondary me-2">
						<i class="align-middle" data-feather="rotate-ccw"></i> {{ __('Reset') }}
					</button>
				</div>
			</div>
		</div>
	</form>
</div>

@push('scripts')
{{ View::make('common/google_map', ['type' => 'autocomplete']) }}
@endpush
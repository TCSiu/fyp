@php
$items_name = old('items_name');
$items_number = old('items_number');
$items_is_remove = old('items_is_remove');
if(isset($record)){
	$product_name_and_number = json_decode($record['product_name_and_number'], true);
	$items_name = array_map(function($arr){return $arr['product_name'];}, $product_name_and_number);
	$items_number = array_map(function($arr){return $arr['product_number'];}, $product_name_and_number);
}
@endphp

<div class="card-body">
	<form action="{{ route('cms.store', ['model' => $model, 'id' => (isset($id)?intval($id):false)]) }}" method="POST" id="orderForm" autocomplete="off" enctype="multipart/form-data">
		@method('PUT')
		@csrf
		<div class="row">
			<div class="col-12 col-md-2 mb-2">
				<label for="orderSex" class="form-label">{{ __('Gender:') }}{!! Utility::required() !!}</label>
				<select class="form-select form-select-md" aria-label=".form-select-md" name="sex" id="orderSex" required>
					<option selected disabled>Select Gender</option>
					<option value="male" @selected(old('sex', $record['sex'] ?? '') == 'male')>Male</option>
					<option value="female" @selected(old('sex', $record['sex'] ?? '') == 'female')>Female</option>
					<option value="x" @selected(old('sex', $record['sex'] ?? '') == 'x')>Gender X</option>
				</select>
			</div>
			<div class="col-12 col-md-5 mb-2">
				<label for="orderFirstName" class="form-label form-required">{{ __('First Name:') }}{!! Utility::required() !!}</label>
				<input type="text" required class="form-control" aria-describedby="orderFirstName" name="first_name" id="orderFirstName" value="{{ $record['first_name'] ?? old('first_name') ?? '' }}" />
			</div>
			<div class="col-12 col-md-5 mb-2">
				<label for="orderLastName" class="form-label form-required">{{ __('Last Name:') }}{!! Utility::required() !!}</label>
				<input type="text" required class="form-control" aria-describedby="orderLastName" name="last_name" id="orderLastName" value="{{ $record['last_name'] ?? old('last_name') ?? '' }}" />
			</div>
		</div>  
		<div class="row">
			<div class="col-12 mb-2">
				<label for="orderDeliver1" required class="form-label form-required">{{ __('Deliver Address:') }}{!! Utility::required() !!}</label>
				<div class="input-group">
					<input type="text" class="form-control" aria-describedby="orderDeliver1" role="presentation" name="delivery1" id="autocomplete" value="{{ $record['delivery1'] ?? old('delivery1') ?? '' }}" />
					<input type="number" step="0.00001" min="0" max="400" class="form-control text-center" placeholder="lat" readonly name="lat" style="max-width:160px;" value="{{ $record['lat'] ?? old('lat') ?? '' }}" />
					<input type="number" step="0.00001" min="0" max="400" class="form-control text-center" placeholder="lng" readonly name="lng" style="max-width:160px;" value="{{ $record['lng'] ?? old('lng') ?? '' }}" />
				</div>
				<h6 class="text-secondary mt-1">Select the deliver address from the drop-down list</h6>
			</div>
			<div class="col-12 mb-2">
				<label for="orderDeliver2" class="form-label">{{ __('Apartment, unit, suite, or floor #:') }}</label>
				<input type="text" class="form-control" aria-describedby="orderDeliver2" role="presentation" name="delivery2" id="orderAddress2" value="{{ $record['delivery2'] ?? old('delivery2') ?? '' }}" />
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-4 mb-2">
				<label for="orderPhoneNumber" required class="form-label form-required">{{ __('Phone Number:') }}{!! Utility::required() !!}</label>
				<input type="tel" class="form-control" aria-describedby="orderPhoneNumber" name="phone_number" id="orderPhoneNumber" value="{{ $record['phone_number'] ?? old('phone_number') ?? '' }}" />
			</div>
			@if(false)
			<!-- <div class="col-12 col-md-8">
				<label for="orderDeliveryDate" required class="form-label form-required">{{ __('Delivery Date:') }}{!! Utility::required() !!}</label>
				<input type="text" class="form-control datepicker" aria-describedby="orderDeliveryDate" name="delivery_date" id="orderDeliveryDate" value="{{ $record['delivery_date'] ?? old('delivery_date') ?? date('Y-m-d', strtotime('+2 day')) }}" />
			</div> -->
			@endif
		</div>
		<div class="row">
			<div class="col-12 mb-2">{{ __('Delivery Items') }}{!! Utility::required() !!}</div>
			<div class="col-12 mb-2">
				<table class="table table-striped table-hover" id="order_table">
					<thead>
						<th>#</th>
						<th class="col-6 col-md-7">Product Name</th>
						<th>Number of Item(s)</th>
						<th>Is Delete</th>
					</thead>
					<tbody id="order_tbody">
					@if(isset($items_name) && isset($items_number))
						@php
							$row_number = 1;
						@endphp
						@for($i = 1; $i <= sizeof($items_name); $i++)
							@if(isset($items_name[$i]) || isset($items_number[$i]))
							<tr>
								<td>
									<span class="order_id">{{ $row_number }}</span>
								</td>
								<td>
									<input type="text" class="form-control" name="items_name[{{ $row_number }}]" id="items_name[{{ $row_number }}" value="{{ $items_name[$i] ?? '' }}" />
								</td>
								<td>
									<input type="number" class="form-control" name="items_number[{{ $row_number }}]" id="items_number[{{ $row_number }}]" value="{{ $items_number[$i] ?? '' }}" />
								</td>
								<td>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="1" name="items_is_remove[{{ $row_number }}]" id="items_is_remove[{{ $row_number }}]" @checked(isset($items_is_remove[$i])) />
									</div>
								</td>
							</tr>
							@php
								$row_number++;
							@endphp
							@endif
						@endfor
					@endif
					</tbody>	
					<tfoot>
						<tr>
							<td colspan="4" class="text-end">
								<button type="button" class="btn btn-outline-primary" id="btn_order_create" data-target="order_tbody" data-template="template_order_row" data-node="tr">Create</button>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-12 mb-2 text-danger"><strong>{!! Utility::required() !!} {{ __('required') }}</strong></div>
		</div>
		<div class="row">
			<div class="col-12 mb-2">
				<button type="submit" class="btn btn-success me-2">
					<i class="align-middle" data-feather="save"></i> {{ __('Save') }}
				</button>
				<button type="reset" class="btn btn-danger me-2">
					<i class="align-middle" data-feather="rotate-ccw"></i> {{ __('Reset') }}
				</button>
				<a href="{{ route('cms.list', ['model' => $model]) }}" class="btn btn-secondary me-2">
					<i class="align-middle" data-feather="x"></i> {{ __('Cancel') }}
				</a>
			</div>
		</div>
	</form>		   
</div>

<template id="template_order_row">
	<td>
		<span class="order_id"></span>
	</td>
	<td>
		<input type="text" class="form-control" name="items_name[%id%]" id="items_name_%id%" />
	</td>
	<td>
		<input type="number" class="form-control" name="items_number[%id%]" id="items_number_%id%" />
	</td>
	<td>
		<div class="form-check">
			<input class="form-check-input" type="checkbox" value="1" name="items_is_remove[%id%]" id="items_is_remove_%id%" />
		</div>
	</td>
</template>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(event){

	// const tbody = document.body.getElementById('order_tbody');
	// let existing = @json($record['product_name_and_number'] ?? []);
	// [...existing].map(function(e){
	// 	let tr = document.body.getElementById('template_order_row');
	// 	tr.innerHTML.replaceAll(/\%id\%/gi, tbody.children.length + 1);
	// 	tr.querySelectorAll('input[name^="items_name["]')[0].value = e.item_name;
	// 	tr.querySelectorAll('input[name^="items_number["]')[0].value = e.items_number;
	// 	tbody.appendChild(tr)
	// });

	document.getElementById('btn_order_create').addEventListener('click', function(e){
		let elem = e.target;
		let template = document.getElementById(elem.getAttribute('data-template'));
		let tbody = document.getElementById(elem.getAttribute('data-target'));
		let node = elem.getAttribute('data-node');
		let temp = document.createElement(node);
		temp.innerHTML = template.innerHTML.replaceAll(/\%id\%/gi, tbody.children.length + 1);
		tbody.appendChild(temp);
		[...document.querySelectorAll('#order_tbody .order_id')].map(function(e, k){e.innerText = k + 1;});
		feather.replace();
	});
});

</script>
{{ View::make('common/google_map', ['type' => 'autocomplete']) }}
@endpush
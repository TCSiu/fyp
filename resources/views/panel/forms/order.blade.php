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
	<form action="{{ route('cms.store', ['model' => $model, 'id' => (isset($id)?intval($id):false)]) }}" method="POST">
		@method('PUT')
		@csrf
		<div class="row mb-3">
			<div class="col-12 col-md-2">
				<label for="orderSex" class="form-label">Gender:</label>
				<select class="form-select form-select-md mb-3" aria-label=".form-select-md" name="sex" id="orderSex">
					<option selected disabled>Select Gender</option>
					<option value="male"{{ ($record['sex'] ?? old('sex')) == 'male' ? 'selected' : '' }}>Male</option>
					<option value="female" {{ ($record['sex'] ?? old('sex')) == 'female' ? 'selected' : '' }}>Female</option>
					<option value="x" {{ ($record['sex'] ?? old('sex')) == 'x' ? 'selected' : '' }}>Gender X</option>
				</select>
			</div>
			<div class="col-12 col-md-5">
				<label for="orderFirstName" class="form-label">First Name:</label>
				<input type="text" class="form-control" aria-describedby="orderFirstName" name="first_name" id="orderFirstName" value="{{ $record['first_name'] ?? old('first_name') ?? '' }}" />
			</div>
			<div class="col-12 col-md-5">
				<label for="orderLastName" class="form-label">Last Name:</label>
				<input type="text" class="form-control" aria-describedby="orderLastName" name="last_name" id="orderLastName" value="{{ $record['last_name'] ?? old('last_name') ?? '' }}" />
			</div>
		</div>  
		<div class="row mb-3">
			<div class="col-12">
				<label for="orderAddress" class="form-label">Address:</label>
				<input type="text" class="form-control" aria-describedby="orderAddress" name="address" id="orderAddress" value="{{ $record['address'] ?? old('address') ?? '' }}" />
			</div>
		</div>
		<div class="row mb-3">
			<div class="col-12 col-md-4">
				<label for="orderPhoneNumber" class="form-label">Phone Number:</label>
				<input type="tel" class="form-control" aria-describedby="orderPhoneNumber" name="phone_number" id="orderPhoneNumber" value="{{ $record['phone_number'] ?? old('phone_number') ?? '' }}" />
			</div>
			<div class="col-12 col-md-8">
				<label for="orderDeliveryDate" class="form-label">Delivery Date:</label>
				<input type="text" class="form-control datepicker" aria-describedby="orderDeliveryDate" name="delivery_date" id="orderDeliveryDate" value="{{ $record['delivery_date'] ?? old('delivery_date') ?? date('Y-m-d') }}" />
			</div>
		</div>
		<table class="table table-striped table-hover" id="order_table">
			<thead>
				<th>#</th>
				<th class="col-6 col-md-7">Product Name</th>
				<th>Number of Item(s)</th>
				<th>Is Delete</th>
			</thead>
			<tbody id="order_tbody">
				@isset($items_name, $items_number)
				@php
					$row_number = 1;
				@endphp
				@for($i = 1; $i <= sizeOf($items_name); $i++)
				@if(isset($items_name[$i]) || isset($items_number[$i]))
				<tr>
					<td>
						<span class="order_id">{{ $row_number }}</span>
					</td>
					<td>
						<input type="text" class="form-control" name="{{ 'items_name[' . $row_number . ']' }}" id="{{ 'items_name_' . $row_number }}" value="{{ $items_name[$i] ?? '' }}" />
					</td>
					<td>
						<input type="number" class="form-control" name="{{ 'items_number[' . $row_number . ']' }}" id="{{ 'items_number[' . $row_number . ']' }}" value="{{ $items_number[$i] ?? '' }}" />
					</td>
					<td>
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="1" name="{{ 'items_is_remove[' . $row_number . ']' }}" id="{{ 'items_is_remove[' . $row_number . ']' }}" {{ isset($items_is_remove[$i]) ? 'checked':'' }} />
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
		<div class="row mb-3">
			<div class="col">
				<button type="submit" class="btn btn-primary me-2">
					<i class="align-middle" data-feather="save"></i> {{ __('Save') }}
				</button>
				<button type="reset" class="btn btn-secondary me-2">
					<i class="align-middle" data-feather="rotate-ccw"></i> {{ __('Reset') }}
				</button>
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
	document.getElementById('btn_order_create').addEventListener('click', function(e){
		var elem = e.target;
		var template = document.getElementById(elem.getAttribute('data-template'));
		var tbody = document.getElementById(elem.getAttribute('data-target'));
		var node = elem.getAttribute('data-node');
		var temp = document.createElement(node);
		temp.innerHTML = template.innerHTML.replaceAll(/\%id\%/gi, tbody.children.length + 1);
		tbody.appendChild(temp);
		[...document.querySelectorAll('#order_tbody .order_id')].map(function(e, k){e.innerText = k + 1;});
		feather.replace();
	});
});
</script>
@endpush
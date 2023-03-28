@extends('common/default')

@section('content')
<main class="content">
	<div class="container-fluid p-0">
		<h1 class="h3 mb-3">{{ __('Content Management System') }}</h1>
		<div class="row justify-content-center">
			<div id="error_alert">
				@includeIf('panel/part/alert')
			</div>
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title">{{ __($inpage_title) }}</h5>
						@if(isset($data))
							<h6 class="card-subtitle text-muted">
								@if($total_count == 0)
									{{ __('There is no records yet.') }}
								@else
									{{ __('Showing :count of :total records.', ['count' => sizeof($data), 'total' => intval($total_count)]) }}
								@endif
							</h6>
							{{ View::make('common/operation', ['model'	=>	$model, 'operations'	=>	$operations]) }}
						@else
							<h6 class="card-subtitle text-muted">{{ __('An error has occured!') }}</h6>
						@endif
					</div>
					@includeIf('common/default_list')
				</div>
			</div>
		</div>
	</div>
</main>

<template id="template_error_alert">
	<div class="alert alert-danger alert-dismissible fade show w-75" role="alert">
		<div class="alert-message" id="template_alert_msg">	

		</div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
</template>
@stop

@hasSection('form-js')
@yield('form-js')
@endif

@if(in_array('assign', $allow_actions))
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    let select_assign = document.getElementById('select_assign_task');

    let btn_assign_modal = document.querySelectorAll('[id^="btn_assign_modal_"]');
	let btn_route_assign = document.getElementById('btn_route_assign');

	let order_id = 0;

    function staffList(item){
        let opt;
		let alert_msg;
        let xhr 					= new XMLHttpRequest();
		let error_alert 			= document.getElementById('error_alert');
		let template_error_alert 	= document.getElementById('template_error_alert');
		order_id 					= item.getAttribute('data-bs-value');

        xhr.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                let json 				= JSON.parse(xhr.responseText);
                select_assign.innerHTML = '';
				opt 					= document.createElement('option');
				if(json.length > 0){
					opt.innerHTML 			= "{{ __('--Select a Staff--') }}";
					opt.value 				= 0;
					opt.selected 			= true;
					opt.disabled 			= true;
					select_assign.appendChild(opt);
					for(let index in json){
						opt = document.createElement('option');
						opt.innerHTML = json[index].first_name + ' ' + json[index].last_name;
						opt.value = json[index].id;
						select_assign.appendChild(opt);
					}
				}else{
					opt.innerHTML 			= "{{ __('No staff account, please create at least one staff!') }}";
					opt.value 				= 0;
					opt.selected 			= true;
					opt.disabled 			= true;
					select_assign.appendChild(opt);
				}
            }else if(this.readyState == 4 && this.status == 404 || this.readyState == 4 && this.status == 500){
				let json = JSON.parse(xhr.responseText);
				error_alert.innerHTML = template_error_alert.innerHTML;
				alert_msg = document.getElementById('template_alert_msg');
				alert_msg.innerHTML = json['data'];
			}
        }
        xhr.open("GET", "{{ route('route.staff', ['company_id' => $company_id]) }}", true);
        xhr.setRequestHeader('content-type', 'application/json');
        xhr.send();
    }

	function assignStaff(){
        let staff_id = select_assign.value;
		if(staff_id == 0){
			return;
		}
        let xhr = new XMLHttpRequest();
        let params = JSON.stringify({'staff_id' : staff_id, 'order_id' : order_id});
        xhr.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				location.reload();
			}
		}
        xhr.open("POST", "{{ route('route.assign') }}", true);
        xhr.setRequestHeader('content-type', 'application/json');
        xhr.send(params);
    }

	[...btn_assign_modal].forEach(function(item){
		item.addEventListener('click', function(){
			staffList(this);	
		});
	});

	if(btn_route_assign !== null){
		btn_route_assign.addEventListener('click', function(){
			assignStaff();
		});
	}
});
</script>
@endpush
@endif
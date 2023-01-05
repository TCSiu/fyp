@php

@endphp
@extends('layouts/default')

@section('content')
<main class="content">
	<div class="container-fluid p-0">
		<h1 class="h3 mb-3">{{ __('Content Management System') }}</h1>
		<div class="row justify-content-center">
			<div class="col-12 col-xl-12">
				<div class="card">
					<div class="card-header">
						<h3 class="text-center">{{ __('Test') }}</h3>
					</div>
					<div class="col-12 col-lg-10">
                        <img class="img-thumbnail" style="width:200px;height:200px;" src="{{ secure_asset('/storage/uploads/'.$image) }}" alt="" title="" />
                    </div>
				</div>
			</div>
		</div>
	</div>
</main>
@stop

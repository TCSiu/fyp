<!DOCTYPE html>
<html lang="en">
<head>
	@include('common/head')
</head>
<body>
	<div class="wrapper">
		@includeIf('common/sidebar')
		<div class="main">
			@includeIf('common/navbar')
			@hasSection('content')
				@yield('content')
			@endif
			@includeIf('common/footer')
		</div>
	</div>
	@includeIf('common/scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
	@include('common/head')
</head>
<body class="m-0 p-0 overflow-hidden vh-100">
	@yield('content')
	@include('common/scripts')
	@yield('scripts')
</body>
</html>
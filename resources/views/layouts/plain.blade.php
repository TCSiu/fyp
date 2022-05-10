<!DOCTYPE html>
<html lang="en">
<head>
	@include('layouts/head')
</head>
<body class="m-0 p-0 overflow-hidden vh-100">
	@yield('content')
	@include('layouts/scripts')
	@yield('scripts')
</body>
</html>
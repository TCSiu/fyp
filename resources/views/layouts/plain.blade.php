<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
</head>
<body class="m-0 p-0 overflow-hidden vh-100">
	@yield('content')
	@include('layouts/scripts')
	@yield('scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
	@include('admin.partials._head')
</head>
<body>
	<header>
		@include('admin.partials._header')
	</header>
	<div class="content">
		@yield('content')
	</div>
	<footer>
		@include('admin.partials._footer')
	</footer>
</body>
</html>
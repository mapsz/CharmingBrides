<!DOCTYPE html>
<html lang="en">
<head>
	@include('partials._head')
</head>
<body>
	<header>
		@include('partials._header')
	</header>
	<div class="content">
		@yield('content')
	</div>
	<footer>
		@include('partials._footer')
	</footer>
</body>
</html>
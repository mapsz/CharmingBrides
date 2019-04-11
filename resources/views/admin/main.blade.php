<!DOCTYPE html>
<html lang="en">
<head>
	@include('admin.partials._head')
</head>
<body>	
	<div id="app">
		<header>
			@include('admin.partials._header')
		</header>
		<div class="content">
			@yield('content')
		</div>
		<footer>
			@include('admin.partials._footer')
		</footer>
	</div>
	<script src='{{asset('js/app.js')}}'></script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
	@include('partials._head')
</head>
<body >
  <div id="app">
  	<header>
  		@include('partials._header')
  	</header>
    <div class="men-noty">
      <men-notysetup />
    </div>
  	<div class="content">
  		@yield('content')
  	</div>
  	<footer>
  		@include('partials._footer')
  	</footer>
  </div>
</body>
</html>
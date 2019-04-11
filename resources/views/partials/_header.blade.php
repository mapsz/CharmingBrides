<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
	<!-- brand -->
	<a class="navbar-brand" href="#">Charming Brides</a>
	<!-- toggle button -->
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<!-- navlist -->
	<div class="maxw container-fluid">
		<div class="collapse navbar-collapse rounded" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<!-- home -->
				<li class="nav-item">
					<a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
				</li>
				<!-- about -->
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					About
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<a class="dropdown-item" href="#">About us</a>
						<a class="dropdown-item" href="#">Branches</a>
						<a class="dropdown-item" href="#">Partnership</a>
					</div>
				</li>
				<!-- girls -->
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Girls
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<a class="dropdown-item" href="#">New Girls</a>
						<a class="dropdown-item" href="#">Online Girls</a>
						<a class="dropdown-item" href="#">Gallery</a>
					</div>
				</li>
				<!-- services -->
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Services
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<a class="dropdown-item" href="#">Membership</a>
						<a class="dropdown-item" href="#">Matchmaker</a>
						<a class="dropdown-item" href="#">Chatting</a>
						<a class="dropdown-item" href="#">Photos & Videos</a>
						<a class="dropdown-item" href="#">Local City Services</a>
					</div>
				</li>	
				<!-- testimonials -->
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Testimonials
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<a class="dropdown-item" href="#">Testimonials</a>
						<a class="dropdown-item" href="#">Our Couples</a>
					</div>
				</li>
				<!-- Help -->
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Help						</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<a class="dropdown-item" href="#">Faq</a>
						<a class="dropdown-item" href="#">How to Start</a>
						<a class="dropdown-item" href="#">Information</a>
					</div>
				</li>
				<!-- contacts -->
				<li class="nav-item">
					<a class="nav-link" href="#">Contacts <span class="sr-only">(current)</span></a>
				</li>	
				<!-- chat -->
				@if(Auth::check())
					<li class="nav-item">
						<a class="nav-link" href="{{ route('chat') }}">Chat<span class="sr-only">(current)</span></a>
					</li>				
				@endif		
			</ul>
			@if(Auth::check())
				<a class="nav-link" href="{{ route('profile') }}">  <i class="icon-user-plus"></i> Profile</a>
				<a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">  <i class="icon-user-plus"></i> Logout</a>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
					@csrf
				</form>

			@else
				<a class="nav-link" href="/login">     <i class="icon-login"></i> Login</a>
				<a class="nav-link" href="{{ route('registration') }}">  <i class="icon-user-plus"></i> Register</a>
			@endif
		</div>
	</div>
</nav>


<!-- /Navbar -->


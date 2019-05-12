@extends('main')

@section('content')

	<div class="container-fluid servicesBlocks">
		<div class="row">
			<!-- Live Assistant -->
			<div class="col-6 col-sm-3 p-1 py-2 servicesBlock">
				<div class="card">					
					<div class="card-body p-1 media">
						<img class="float-left pr-2 align-self-center" src="public/img/services/live_assistant.png" alt="Live Assistant">
						<div class="card-text align-self-center">
							<h5 class="card-title">Live Assistant</h5>						
							<p class="card-text">Our Live Assistant will help you to get best of our service</p>
						</div>
					</div>
				</div>	
			</div>
			<!-- Highest Security -->
			<div class="col-6 col-sm-3 p-1 py-2 servicesBlock">
				<div class="card">					
					<div class="card-body p-1 media">
						<img class="float-left pr-2 align-self-center" src="public/img/services/security.png" alt="Live Assistant">
						<div class="card-text align-self-center">
							<h5 class="card-title">Highest Security</h5>						
							<p class="card-text">Learn more about our Security and Anti-SCAM programs we support</p>
						</div>
					</div>
				</div>	
			</div>
			<!-- Our services -->
			<div class="col-6 col-sm-3 p-1 py-2 servicesBlock">
				<div class="card">					
					<div class="card-body p-1 media">
						<img class="float-left pr-2 align-self-center" src="public/img/services/services.png" alt="Live Assistant">
						<div class="card-text align-self-center">
							<h5 class="card-title">Our Services</h5>						
								<ul>
									<li><a href="">All services</a></li>
									<li><a href="">Membership</a></li>
									<li><a href="">Gifts</a></li>
									<li><a href="">Flowers</a></li>
									<li><a href="">Information</a></li>
								</ul>		
						</div>
					</div>
				</div>	
			</div>
			<!-- Romantic tour -->
			<div class="col-6 col-sm-3 p-1 py-2 servicesBlock">
				<div class="card">					
					<div class="card-body p-1 media">
						<img class="float-left pr-2 align-self-center" src="public/img/services/romantic_tour.png" alt="Live Assistant">
						<div class="card-text align-self-center">
							<h5 class="card-title">Romantic Tour</h5>						
							<p class="card-text">Learn more about Romantic Tour to Odessa</p>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</div>

	<!-- title -->
	<div class="container-fluid title py-2 maxw">
		<div class="titleContainer media px-lg-4">				
			<div class="align-self-center d-none d-sm-block">
				<button class="btn ml-2 p-1">	
					<i class="icon-angle-double-left"></i>
					Online Ladies
				</button>
			</div>
			<div  class="media-body">
				<h2 class="text-center">Special Ladies</h2>
			</div>
			<div class="align-self-center d-none d-sm-block">
				<button class="btn mr-2 p-1">
					New Ladies 
					<i class="icon-angle-double-right"></i>
				</button>
			</div>
		</div>	
	</div>
	<!-- ladies list -->
	<girls-special-ladies-component />

@endsection
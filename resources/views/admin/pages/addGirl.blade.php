@extends('admin.main')

@section('content')

<div class="container pt-3">
	
<script src='{{asset('adminAssets/js/main.js')}}' defer></script>
	<button class="rand">rand</button>


	<h1>Add Girl</h1>
	<form id='addGirlForm' method="post" action="{{ route('adminGirlStore') }}">
		@csrf
		{{-- Name --}}
		@php ($input = array('name' => 'name','field' => 'input', 'type' => 'text', 'placeholder' => "Anastasiya"))
		<div class="form-group row">
		  <label for="{{$input['name']}}" class="col-sm-2 col-form-label">Name:</label>
		  <div class="col-sm-10">
			@include('admin.partials._input')
		  </div>
		</div>
		{{-- Birth --}}
		<div class="form-group row">
			<label for="day" class="col-sm-2 col-form-label">Date of Birth:</label>
			<div class="col-sm-10">
				<div class="input-group">
					{{-- Day --}}
					@php ($input = array('name' => 'day','field' => 'numberSelect', 'i' => 31))
					<div class="input-group-prepend">
						<label for="{{$input['name']}}" class="input-group-text">Day:</label>
					</div>
					@include('admin.partials._input')
					{{-- Month --}}
					@php ($input = array('name' => 'month','field' => 'listSelect', 'values' => array('January','February','March','April','May','June','July','August','September','October','November','December')))	
					<div class="input-group-prepend">
						<label for="{{$input['name']}}" class="input-group-text">Month:</label>
					</div>
					@include('admin.partials._input')
		   			{{-- Year --}}
		   			@php ($input = array('name' => 'year','field' => 'input', 'type' => 'number', 'placeholder' => 1992))
		   			<div class="input-group-prepend">
						<label for="{{$input['name']}}" class="input-group-text">Year:</label>
					</div>
					@include('admin.partials._input')
				</div>
			</div>
		</div>
		{{-- Location --}}		
		@php ($input = array('name' => 'location','field' => 'input', 'type' => 'text', 'placeholder' => "Kiev"))
		<div class="form-group row">
		  <label for="{{$input['name']}}" class="col-sm-2 col-form-label">Location:</label>
		  <div class="col-sm-10">
		    @include('admin.partials._input')
		  </div>
		</div>
		{{-- Weight --}}
		@php ($input = array('name' => 'weight','field' => 'input', 'type' => 'number', 'placeholder' => 64))
		<div class="form-group row">
		  <label for="{{$input['name']}}" class="col-sm-2 col-form-label">Weight(kg):</label>
		  <div class="col-sm-10">
		    @include('admin.partials._input')
		  </div>
		</div>	
		{{-- Height --}} 
		@php ($input = array('name' => 'height','field' => 'input', 'type' => 'number', 'placeholder' => 168))
		<div class="form-group row">
			<label for="{{$input['name']}}" class="col-sm-2 col-form-label">Height(cm):</label>
			<div class="col-sm-10">
				@include('admin.partials._input')		  
			</div>
		</div>	
		{{-- Hair  --}}
		@php ($input = array('name' => 'hair','field' => 'listSelect', 'values' => array('Black','Blond','Brown','Fair','Red','Other')))	
		<div class="form-group row">
			<label for="{{$input['name']}}" class="col-sm-2 col-form-label">Hair Color:</label>
			<div class="col-sm-10">
	   			@include('admin.partials._input')				
			</div>
		</div>	
		{{-- Eyes   --}}
		@php ($input = array('name' => 'eyes','field' => 'listSelect', 'values' => array('Blue','Green','Grey','Hazel','Other')))	
		<div class="form-group row">
			<label for="{{$input['name']}}" class="col-sm-2 col-form-label">Eyes Color:</label>
			<div class="col-sm-10">
	   			@include('admin.partials._input')			
			</div>
		</div>	
		{{-- Religion  --}}
		@php ($input = array('name' => 'religion','field' => 'listSelect', 'values' => array('Christianity','Islam','Nonreligious','Other')))	
		<div class="form-group row">
			<label for="{{$input['name']}}" class="col-sm-2 col-form-label">Religion:</label>
			<div class="col-sm-10">
	   			@include('admin.partials._input')				
			</div>
		</div>	
		{{-- Education  --}}
		@php ($input = array('name' => 'education','field' => 'listSelect', 'values' => array('Associate Degree','College','High School','Student','University','Other')))	
		<div class="form-group row">
			<label for="{{$input['name']}}" class="col-sm-2 col-form-label">Education:</label>
			<div class="col-sm-10">
				@include('admin.partials._input')
			</div>
		</div>	
		{{-- Profession  --}}
		@php ($input = array('name' => 'profession','field' => 'input', 'type' => 'text', 'placeholder' => "Hairdresser"))
		<div class="form-group row">
		  <label for="{{$input['name']}}" class="col-sm-2 col-form-label">Profession:</label>
		  <div class="col-sm-10">
		  	@include('admin.partials._input')
		  </div>
		</div>	
		{{-- Maritial status  --}}
		@php ($input = array('name' => 'maritial','field' => 'listSelect', 'values' => array('Never married','Divorced','Widowed')))	
		<div class="form-group row">
			<label for="{{$input['name']}}" class="col-sm-2 col-form-label">Maritial status:</label>
			<div class="col-sm-10">
				@include('admin.partials._input')
		  	</div>
		</div>	
		{{-- Children  --}}
		@php ($input = array('name' => 'children','field' => 'listSelect', 'values' => array('No','1','2','3','4','5','6','7','8','9','10+')))	
		<div class="form-group row">
			<label for="{{$input['name']}}" class="col-sm-2 col-form-label">Children:</label>
			<div class="col-sm-10">				
				@include('admin.partials._input')
			</div>
		</div>	
		{{-- Smoking  --}}
		@php ($input = array('name' => 'smoking','field' => 'listSelect', 'values' => array('No','Yes')))
		<div class="form-group row">
			<label for="{{$input['name']}}" class="col-sm-2 col-form-label">Smoking:</label>
			<div class="col-sm-10">
				@include('admin.partials._input')
			</div>
		</div>	
		{{-- Alcohol  --}}
		@php ($input = array('name' => 'alcohol','field' => 'listSelect', 'values' => array('No','Socially')))	
		<div class="form-group row">
			<label for="{{$input['name']}}" class="col-sm-2 col-form-label">Alcohol:</label>
			<div class="col-sm-10">
				@include('admin.partials._input')
			</div>
		</div>	
		{{-- English proficiency: --}}
		@php ($input = array('name' => 'english','field' => 'listSelect', 'values' => array('Fluent','Good','Medium','Poor','Some')))
		<div class="form-group row">
			<label for="{{$input['name']}}" class="col-sm-2 col-form-label">English proficiency:	</label>
			<div class="col-sm-10">
				@include('admin.partials._input')
			</div>
		</div>	
		{{-- Other languages: --}}
		@php ($input = array('name' => 'otherLanguages','field' => 'input', 'type' => 'text', 'placeholder' => "Spanish(native), Chinese(fluent)"))
		<div class="form-group row">
		  <label for="{{$input['name']}}" class="col-sm-2 col-form-label">Other Languages:</label>
		  <div class="col-sm-10">
		  	@include('admin.partials._input')
		  </div>
		</div>	
		{{-- Preffered men's age: --}}
		<div class="form-group row">
			<label for="prefferfrom" class="col-sm-2 col-form-label">Preffered men's age:	</label>
			<div class="col-sm-10">
				<div class="input-group">
		   			{{-- manAgefrom --}}
					@php ($input = array('name' => 'prefferfrom','field' => 'input', 'type' => 'number', 'placeholder' => 32))
		   			<div class="input-group-prepend">
						<label for="{{$input['name']}}" class="input-group-text">From:</label>
					</div>
					@include('admin.partials._input')   			
		   			{{-- To --}}
					@php ($input = array('name' => 'prefferTo','field' => 'input', 'type' => 'number', 'placeholder' => 48))
	   				<div class="input-group-prepend">
						<label for="{{$input['name']}}" class="input-group-text">To:</label>
					</div>
		   			@include('admin.partials._input')   
				</div>
			</div>
		</div>	
		{{-- Email address --}}
		@php ($input = array('name' => 'email','field' => 'input', 'type' => 'email', 'placeholder' => "anastasiya@example.com"))
		<div class="form-group row">
		  <label for="{{$input['name']}}" class="col-sm-2 col-form-label">Email address:</label>
		  <div class="col-sm-10">
		  	@include('admin.partials._input')
		  </div>
		</div>	
		{{-- Info --}}
		@php ($input = array('name' => 'info','field' => 'textarea', 'rows' => 8))
		<?$input['placeholder'] = 
			"I like travelling, it is the most exciting thing in the world! It`s so wonderful and interesting to learn new places, to meet new people! I dream to see the whole world! I am calm, intelligent and well bred lady. I am here because I know that foreign men are more clever, more intellignet and more family oriented than our Ukrainian men. My character is not difficult, I can find compromises, I am tender in love, that`s why i am sure my man will be happy with me. And of course I dream he will make me happy also. \n" . 
			"I am not looking for ideal man, but I would like he would have kind heart, open character, of course he should be attentive to me, because I want to feel his attention, understanding and support. I promise to give all myself to such man!";
		?>
		<div class="form-group row">
		  <label for="{{$input['name']}}" class="col-sm-2 col-form-label">Info:	</label>
		  <div class="col-sm-10">
		  	@include('admin.partials._input')
		  </div>
		</div>	
		{{-- First Letter Subject --}}
		@php ($input = array('name' => 'firstLetterSubject','field' => 'input', 'type' => 'text', 'placeholder' => "My dream is to love"))
		<div class="form-group row mb-0">
		  <label for="{{$input['name']}}" class="col-sm-2 col-form-label">First Letter Subject:</label>
		  <div class="col-sm-10">
		  	@include('admin.partials._input')
		  </div>
		</div>	
		{{-- First Letter --}}
		@php ($input = array('name' => 'firstLetter','field' => 'textarea', 'rows' => 8))
		<?$input['placeholder'] = 
			"My name is Alena) I am a student of Melitopol State University of Municipal Management; I entered the department of hotel business because I like to manage people, to develop different ideas and turn them into reality. My plan is to start a network of hotels one day! :)\n" . 
			"I am living in the city of Melitopol, I live with my family in a private house. My family is not very big: my mother Lyudmila, father Yuriy and my little sister Kate. I love to spend time with my family, my sister and I love to draw together) \n" . 
			"When I have free time I read. I enjoy reading fantasy books, among my favorite authors are Joan Rowling and Ray Bradbury. I also love watching films, my favorite genre is melodrama. Sometimes I watch a film to relax, but I try to find the films that provoke deep thinking, that have an intricate plot and make you feel what the characters feel. Sometimes books and films teach us a lot. Do you agree?\n" . 
			"My main hobby is dancing. I dance in different styles, but my favorite one is jazz-funk! When I perform on the stage I get so many emotions, this is a real charge of positive! I take part in different competitions and fashion shows, too! From time to time I take part in photo sessions and I truly like this! Do you like to take pictures? Or you prefer to be taken pictures of?\n" . 
			"I love animals and I have 2 pets) They are a cat and a dog! What a funny combination! Surprisingly, but they are good friends! \n" . 
			"I love travelling, but at the moment I have travelled only around my country. I have visited different cities among them are Kiev, Lviv, Zaporozhye. I enjoyed my stay in Kiev, it is a very beautiful city with fascinating architecture and places to see. It has also a great choice of shopping malls! You can find an activity to your liking in Kiev! It has a lot to offer! \n" . 
			"I can call myself a kind and brave, communicative and cheerful lady! I am always glad to help my close ones and friends. I decided to post my profile on the site because I believe that my chosen one is not necessarily in Ukraine, he can be anywhere! So I widen the horizons of my search and hope to find you, my soul mate, very soon!\n" . 
			"I have lots of dreams, but my biggest one is to create a happy family with a strong-willed, caring, kind, generous and loving man! A man, who can support me in any situation and be my second half. \n" . 
			"I am looking forward to your letter!\n" . 
			"Warmly,\n" . 
			"Alena\n";
		?>
		<div class="form-group row">
		  <label for="{{$input['name']}}" class="col-sm-2 col-form-label">First Letter:</label>
		  <div class="col-sm-10">
			@include('admin.partials._input')
		  </div>
		</div>
		{{-- Information for Admintrator --}}
		<div class="form-group row">
			<label for="forAdminName" class="col-sm-2 col-form-label">Information for Admintrator:</label>
			<div class="col-sm-10">
				<div class="input-group">
		   			{{-- Name --}}
					@php ($input = array('name' => 'forAdminName','field' => 'input', 'type' => 'text', 'placeholder' => "Anastasiya"))
	   				<div class="input-group-prepend">
						<label for="{{$input['name']}}" class="input-group-text">Name:</label>
					</div>
		   			@include('admin.partials._input')
		   			{{-- Surname --}}
					@php ($input = array('name' => 'forAdminSurname','field' => 'input', 'type' => 'text', 'placeholder' => "Sudcenko"))
		   			<div class="input-group-prepend">
						<label for="{{$input['name']}}" class="input-group-text">Surname:</label>
					</div>
		   			@include('admin.partials._input')
		   			{{-- Fathers Name --}}
		 			@php ($input = array('name' => 'forAdminFathersName','field' => 'input', 'type' => 'text', 'placeholder' => "Petrova"))
  					<div class="input-group-prepend">
						<label for="{{$input['name']}}" class="input-group-text">Fathers Name:</label>
					</div>
		   			@include('admin.partials._input')
		   			{{-- Phone Number --}}
		   			@php ($input = array('name' => 'forAdminPhoneNumber','field' => 'input', 'type' => 'text', 'placeholder' => "09543486572"))
					<div class="input-group-prepend">
						<label for="{{$input['name']}}" class="input-group-text">Phone Number:</label>
					</div>	   			
		   			@include('admin.partials._input')
				</div>
			</div>
		</div>
		{{-- Photos --}}
		@php ($input = array('name' => 'photos', 'field' => 'input', 'type' => 'hidden', 'value'=>'[""]'))
		<div class="{{$input['name']}} form-group row">
			<label for="{{$input['name']}}" class="col-sm-2 col-form-label">Photos:</label>
			{{-- Uploaded count --}}
			<div class="col-1 px-0 m-auto">
				<span class="uploadedCount">?</span>/<span class="maxCount">?</1span>
			</div>
			<div class="col-sm-9">
				@include('admin.partials._input')	
				{{-- Modal Open Button --}}
				<button type="button" class="btn" data-toggle="modal" data-target="#photoModal">Edit Photos</button>
				{{-- Modal --}}
				<div id="photoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Photos</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								{{-- File Upload --}}
								<div class="uploadContainer">
									<label class="w-100" for="photosInput">
										{{-- Drop Area --}}
										<div class="dropArea" style="border:dashed 2px blue;">
											<div class="row">
												<i class="icon-upload mx-auto upload"></i>
											</div>
											<div class="row">
											<span class="mx-auto">
												<b><u>Choose</u> or Drag-and-Drop Photos</b>
											</span>
											</div>
										</div>
									</label>
									<input class="choose d-none" type="file" id='photosInput' multiple>
								</div>
								{{-- Text --}}
								<div class="text alert alert-primary text-center" role="alert">
									Please upload from 5 to 10 Photos!
								</div>
								{{-- Prieview --}}
								<div class="preview">
									<div class="row">
										@for ($i = 0; $i <= 4; $i++)							
											<div class="col">
												<i class="icon-cancel delete" data-id="{{$i}}"></i>
												<img class="item mb-1" src="{{asset('adminAssets/img/preview.png')}}" data-id="{{$i}}">	
											</div>							
										@endfor		
									</div>
									<div class="row">
										@for ($i = 5; $i <= 9; $i++)	
											<div class="col">
												<i class="icon-cancel delete" data-id="{{$i}}"></i>
												<img class="item mb-1" src="{{asset('adminAssets/img/preview.png')}}" data-id="{{$i}}">
											</div>							
										@endfor		
									</div>
								</div>		
							</div>
							<div class="modal-footer">
								<button type="button" class="save btn btn-success" data-dismiss="modal">Save</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- Passport --}}
		@php ($input = array('name' => 'passport','field' => 'input', 'type' => 'hidden', 'value'=>'[""]'))
		<div class="passport form-group row">
			<label for="passport" class="col-sm-2 col-form-label">Passport:</label>			
			{{-- Uploaded count --}}
			<div class="col-1 px-0 m-auto">
				<span class="uploadedCount">?</span>/<span class="maxCount">?</1span>
			</div>
			<div class="col-sm-9">
				@include('admin.partials._input')			
				{{-- Modal Open Button --}}
				<button type="button" class="btn" data-toggle="modal" data-target="#passportModal">Edit Passport</button>
				{{-- Modal --}}
				<div id="passportModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Edit Passport</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								{{-- File Upload --}}
								<div class="uploadContainer">
									<label class="w-100" for="passportInput">
										<div class="dropArea" style="border:dashed 2px blue;">
											<div class="row">
												<i class="icon-upload mx-auto upload"></i>
											</div>
											<div class="row">
											<span class="mx-auto">
												<b><u>Choose</u> or Drag-and-Drop Passport</b>
											</span>
											</div>
										</div>
									</label>
									<input class="choose d-none" type="file" id='passportInput'>
								</div>
								{{-- Text --}}
								<div class="text alert alert-primary text-center" role="alert">
									Please upload Passport copy!
								</div>
								{{-- Prieview --}}
								<div class="preview">
									<div class="row">
										<div class="col-4 offset-4">
											<i class="icon-cancel delete" data-id="0"></i>
											<img class="item mb-1" src="{{asset('adminAssets/img/passportPreview.png')}}" data-id="0">
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="save btn btn-success" data-dismiss="modal">Save</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- Video --}}
		@php ($input = array('name' => 'video','field' => 'input', 'type' => 'hidden', 'value'=>''))
		<div class="video form-group row">
			<label for="video" class="col-sm-2 col-form-label">Video:</label>
			{{-- Uploaded count --}}
			<div class="col-1 px-0 m-auto">
				<span class="uploadedCount">?</span>/<span class="maxCount">?</1span>
			</div>
			<div class="col-sm-9">
				{{-- input --}}
				@include('admin.partials._input')
				{{-- Modal Open Button --}}
				<button type="button" class="btn" data-toggle="modal" data-target="#videoModal">Edit Video</button>
				{{-- Modal --}}
				<div id="videoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Edit Video</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								{{-- File Upload --}}
								<div class="uploadContainer">
									<label class="w-100" for="videoInput">
										<div class="dropArea" style="border:dashed 2px blue;">
											<div class="row">
												<i class="icon-upload mx-auto upload"></i>
											</div>
											<div class="row">
											<span class="mx-auto">
												<b><u>Choose</u> or Drag-and-Drop Video</b>
											</span>
											</div>
										</div>
									</label>
									<input class="choose d-none" type="file" id='videoInput'>
								</div>
								{{-- Text --}}
								<div class="text alert alert-primary text-center" role="alert">
									Please upload short video(1 minute max)
								</div>
								{{-- Prieview --}}							
								<div class="row">
									<div class="col-6 offset-3">
										{{-- Preview --}}
										<div class="preview">
											<img class="videoPreview mb-1" src="{{asset('adminAssets/img/videoPreview.png')}}">
										</div>
										{{-- Remove --}}
										<div class="col-12">
											<button class="delete">
												<i class="icon-cancel" data-id="0"></i>
												Remove
											</button>
										</div>
										{{-- Video --}}
										<video class="w-100" id="girlVideo" controls>
											<source class="item" src="//vjs.zencdn.net/v/oceans.mp4" type="video/mp4"></source>
											<p class="vjs-no-js">
												To view this video please enable JavaScript, and consider upgrading to a
												web browser that
												<a href="http://videojs.com/html5-video-support/" target="_blank">
													supports HTML5 video
												</a>
											</p>
										</video>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="save btn btn-success" data-dismiss="modal">Save</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		{{-- ERRORS --}}
		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		{{-- ADD GIRL --}}
		<button type="send" id="addGirl" class="btn btn-outline-success btn-lg btn-block my-3"><b>Add Girl</b></button>
	</form>
</div>


@endsection
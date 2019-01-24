@extends('main')

@section('content')

	<div class="container py-4">
		
		<h1>Profile</h1>
		{{-- Name --}}
		<div class="row">
			<div class="col-2">Name:</div>
			<div class="col-10">John</div>
		</div>
		{{-- Surname --}}
		<div class="row">
			<div class="col-2">Surname:</div>
			<div class="col-10">Smith</div>
		</div>
		{{-- Email --}}
		<div class="row">
			<div class="col-2">Email:</div>
			<div class="col-10">{{$men['email']}}</div>
		</div>

	</div>

@endsection
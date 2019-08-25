@extends('main')

@section('content')

	<div class="container py-4">
		
		<h1>Registration</h1>
		<form id='addManForm' method="post" action="{{ route('manStore') }}">
			@csrf

			{{-- Email --}}
			@php ($input = array('name' => 'email',	'field' => 'input','type' => 'email','placeholder' => "john.smith@gmail.com"))
			@include('partials._input')

			{{-- Password --}}
			@php ($input = array('name' => 'password',	'field' => 'input','type' => 'password','placeholder' => ""))
			@include('partials._input')

			{{-- Password Confirm--}}
			@php ($input = array('name' => 'password-confirm', '_name' => 'password_confirmation', 'field' => 'input','type' => 'password','placeholder' => ""))
			@include('partials._input')

			{{-- Name --}}
			@php ($input = array('name' => 'name','field' => 'input','type' => 'text','placeholder' => "John"))
			@include('partials._input')

			{{-- Surname --}}
			@php ($input = array('name' => 'surname','field' => 'input','type' => 'text','placeholder' => "Smith"))
			@include('partials._input')

			{{-- Errors --}}
			@if ($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			{{-- Register --}}
			<button type="send" id="register" class="btn btn-outline-success btn-lg btn-block my-3"><b>Register</b></button>

		</form>

	</div>

@endsection
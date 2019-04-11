@extends('main')

@section('content')

	<div id="app">
		<chat class="chatContainer" :prop_user="{{ $user }}"></chat>		
	</div>
	
@endsection
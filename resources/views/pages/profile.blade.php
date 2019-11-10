@extends('main')

@section('content')

	<div>
    @if(Auth::user()->role && Auth::user()->role == 2)
      <man-control></man-control>
    @elseif(Auth::user()->role && Auth::user()->role > 2)
      <man-to-control p-id = "{{$id}}"></man-to-control>
    @endif    
    <man-profile p-data = "{{$data}}"></man-profile>

	</div>

@endsection
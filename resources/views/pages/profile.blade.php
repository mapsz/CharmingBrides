@extends('main')

@section('content')

	<div>

    @if(Auth::user()->role && Auth::user()->role == 2)
      <man-control></man-control>
    @elseif(Auth::user()->role && Auth::user()->role > 2)
      <man-to-control p-id = "{{$id}}"></man-to-control>
    @endif    

    @if(Auth::user()->role && Auth::User()->role == 4)
      <man-admin p-id = "{{$id}}"></man-admin>
    @endif    

    <man-profile p-data = "{{$data}}"></man-profile>

	</div>

@endsection
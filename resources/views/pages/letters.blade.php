@extends('main')

@section('content')

  @if(json_decode($user)->man > 2)
    <letter-admin-component :p-user="{{$user}}">
  @else
    <letter-component :p-user="{{$user}}"/>
  @endif
@endsection
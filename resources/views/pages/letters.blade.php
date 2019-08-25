@extends('main')

@section('content')

  @if(json_decode($user)->man > 2)
    <letter-admin-component>
  @else
    <letter-component :p-user="{{$user}}"/>
  @endif
@endsection
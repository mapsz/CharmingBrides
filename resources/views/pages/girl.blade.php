@extends('main')

@section('content')

  <girl-component prop_girl = "{{$girl}}" prop_auth="{{Auth::check()}}" />

@endsection
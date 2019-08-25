@extends('main')

@section('content')

  <girl-component p-girl = "{{$girl}}" p-auth="{{$auth}}" p-user-is-man="{{$userIsMan}}" />

@endsection
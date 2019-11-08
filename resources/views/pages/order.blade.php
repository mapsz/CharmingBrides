@extends('main')

@section('content')

  <order :p-order="{{$order}}" :p-cat="'{{$cat}}'" :p-user-id="'{{Auth::User()->id}}'"/>

@endsection
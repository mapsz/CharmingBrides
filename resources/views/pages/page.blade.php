@extends('main')

@section('content')

  <component :is="'{{$page}}'"></component>

@endsection
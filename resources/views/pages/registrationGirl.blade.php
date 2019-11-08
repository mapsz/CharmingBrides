@extends('main')

@section('content')


  <men-registration
    class="py-4"
    :p-inputs="{{ $inputs }}" 
    :p-route ="{{ $route }}"
  />
@endsection
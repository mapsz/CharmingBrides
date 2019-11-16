@extends('admin.main')

@section('content')

  <component 
    :is="'{{$vue}}'" 
    @if(isset($data))
      :p-data="'{{$data}}'"
    @endif
  > </component>

@endsection
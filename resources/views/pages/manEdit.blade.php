@extends('main')

@section('content')
  <admin-panel-create-wrapper-component 
    class="py-4"
    :p-name="{{ $name }}"  
    :p-inputs="{{ $inputs }}" 
    :p-route ="{{ $route }}" 
    :p-settings ="{{ $settings }}"
    :p-edit-data ="{{ $editData }}" 
  />
@endsection
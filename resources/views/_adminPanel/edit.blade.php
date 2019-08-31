@extends('admin.main')

@section('content')
  <admin-panel-create-wrapper-component 
    :p-name="{{ $name }}"  
    :p-inputs="{{ $inputs }}" 
    :p-route ="{{ $route }}" 
    :p-settings ="{{ $settings }}" 
    :p-edit-data ="{{ $editData }}" 
  />
@endsection
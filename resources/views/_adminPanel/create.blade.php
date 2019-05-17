@extends('admin.main')

@section('content')
	<admin-panel-create-wrapper-component 
    :prop_name="{{ $name }}"  
    :prop_inputs="{{ $inputs }}" 
    :prop_route ="{{ $route }}" 
    :prop_settings ="{{ $settings }}" 
  />
@endsection
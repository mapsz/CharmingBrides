@extends('admin.main')

@section('content')
	<admin-panel-list-component 
    :prop_name="{{ $name }}" 
    :prop_data="{{ $data }}" 
    :prop_inputs="{{ $inputs }}" 
    :prop_route="{{ $route }}" 
    :prop_settings="{{ $settings }}" 
  />
@endsection
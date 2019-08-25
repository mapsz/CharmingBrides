@extends('admin.main')

@section('content')
	<admin-panel-main-component 
    :p-name="{{ $name }}" 
    :p-data="{{ $data }}" 
    :p-inputs="{{ $inputs }}" 
    :p-route="{{ $route }}" 
    :p-settings="{{ $settings }}" 
  />
@endsection
@extends('admin.main')

@section('content')
  <div class="container-fluid">
    <div class="row">
      {{-- Settings --}}
      <div class="col-3">
        <long-letter-config-component />
      </div>

      {{-- List --}}
      <div class="col-9">      
        <admin-panel-list-component 
          :prop_name="{{ $name }}" 
          :prop_data="{{ $data }}" 
          :prop_inputs="{{ $inputs }}" 
          :prop_route="{{ $route }}" 
          :prop_settings="{{ $settings }}" 
        />
      </div>
    </div>
  </div>


@endsection
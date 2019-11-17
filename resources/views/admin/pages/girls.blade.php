@extends('admin.main')

@section('content')
	
<div class="row">
  
  @if(Auth::user()->role == 4)
    <div class="col-2">
      <admin-girls-special-ladies-component>
    </div>
  @endif
  
  <div class="col-10">
    <admin-panel-main-component 
      :p-name="{{ $name }}" 
      :p-data="{{ $data }}" 
      :p-inputs="{{ $inputs }}" 
      :p-route="{{ $route }}" 
      :p-settings="{{ $settings }}" 
    />  
  </div>

</div>

@endsection
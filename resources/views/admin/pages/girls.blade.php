@extends('admin.main')

@section('content')
	
    <div class="container-fluid">
    	<div class="row">
	    	<div class="col-3">		
	    		{{-- Add New	 --}}	
	    		<div class="col-12 p-5">
		  	        <a href="{{ route('adminGirlCreate') }}">
			            <button class="btn btn-primary">
			                Add New Girl
			            </button>
			        </a>
		        </div>
				{{-- Special Ladies --}}
				<admin-girls-special-ladies-component />
	    	</div>
	    	<div class="col-9">

				<girls-list-component :girls="{{$girls}}"/>
			</div>
		</div>
    </div>

@endsection
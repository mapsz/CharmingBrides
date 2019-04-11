@extends('admin.main')

@section('content')
	
    <div class="container">
        <a href="{{ route('adminGirlCreate') }}">
            <button class="btn btn-primary">
                Add New
            </button>
        </a>

		<girls-component :girls="{{$girls}}"></girls-component>
    </div>

@endsection
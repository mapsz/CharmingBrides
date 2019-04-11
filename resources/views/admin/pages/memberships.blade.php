@extends('admin.main')

@section('content')	
	<div class="container">
        <a href="{{ route('adminMembershipCreate') }}">
            <button class="btn btn-primary">
                Add New
            </button>
        </a>

		<memberships-component :memberships="{{$memberships}}"></memberships-component>	
	</div>
@endsection
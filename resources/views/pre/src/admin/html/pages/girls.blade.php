@extends('admin.main')

@section('content')

	<div class="container-fluid">

		<div class="container">
			<h1>Girl</h1>
			<table class="table table-striped">
				<thead  class="thead-dark">
					<tr>
						<th scope="col">#</th>
						<th scope="col">Name</th>
						<th scope="col">Birth</th>
						<th scope="col">Location</th>
					</tr>
				</thead>
				<tbody>
					@foreach($girls as $girl)
						<tr>
							<td>{{ $girl->id }}</td>
							<td>{{ $girl->name }}</td>
							<td>{{ $girl->birth }}</td>
							<td>{{ $girl->location }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>

		</div>

		<div class="row">
			<div class="col-2">
				<a href="{{ route('adminGirlCreate') }}">
					<button class="btn">
						Add New
					</button>
				</a>
			</div>
			<div class="col-10">
				
			</div>
		</div>
	</div>

@endsection
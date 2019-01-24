@extends('admin.main')

@section('content')

	<div class="container-fluid">

		<div class="row">
			<table>
				<th>
					<tr>1</tr>
					<tr>2</tr>
					<tr>3</tr>
					<tr>4</tr>
				</th>
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
@if (!isset($input['_name'])) @php($input['_name'] = $input['name'])
@endif

<div class="form-group row">
	<label 
		for="{{$input['name']}}" 
		class="col-sm-2 col-form-label" 
		style="text-transform:capitalize;"
		>
			{{$input['name']}}:
	</label>
	<div class="col-sm-10">

		@if ($input['field'] == 'listSelect')

			{{-- List Select --}}
			<select class="custom-select" name="{{$input['name']}}" id="{{$input['name']}}">
				<option {{(old($input['name']) ? "":"selected")}} value=0>Choose...</option>
				@foreach ($input['values'] as $k => $v)
					<option value="{{$k+1}}" {{(old($input['name']) == $k+1 ? "selected":"")}}>{{$v}}</option>
				@endforeach
			</select>

		@elseif ($input['field'] == 'numberSelect')
			
			{{-- Numbers Select --}}
			<select class="custom-select" name="{{$input['name']}}" id="{{$input['name']}}">
				<option {{(old($input['name']) ? "":"selected")}} value=0>Choose...</option>
				@for ($i = 1; $i <= $input['i']; $i++)
					<option value="{{$i}}" {{(old($input['name']) == $i ? "selected":"")}} >{{$i}}</option>
				@endfor
			</select>

		@elseif ($input['field'] == 'input')
			<?	
				if(!isset($input['placeholder'])) 		$input['placeholder'] = "";	
				if(!isset($input['value']))				$input['value'] = "";
				if(old($input['name']) !== null)		$input['value'] = old($input['name']);
			?>

			{{-- Symple Input --}}
			<input  
				class="form-control" 
				type="{{$input['type']}}" 
				name="{{$input['_name']}}" 
				id="{{$input['name']}}" 
				value="{{$input['value']}}" 
				placeholder="{{$input['placeholder']}}"
			>

		@elseif ($input['field'] == 'textarea')
			
			{{-- Text Area --}}
		    <textarea 
			    class="form-control" 
			    name="{{$input['name']}}" 
			    id="{{$input['name']}}" 
			    rows={{$input['rows']}}
			    placeholder="{{$input['placeholder']}}" 
		    >{{old($input['name'])}}</textarea>

		@endif

	</div>
</div>
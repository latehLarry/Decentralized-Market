@extends('product')

@section('product-form')

<div class="h3 mt-10">Product delivery methods</div>
<table class="zebra table-space">
	<thead>
		<tr>
			<th>name</th>
			<th>number of days</th>
			<th>price(in USD)</th>
			<th>preview</th>
			<th>#</th>
		</tr>
	</thead>
	<tbody>
		@if($section == 'edit')
			@forelse($product->deliveries as $delivery)
			<tr>
				<td>{{ $delivery->name }}</td>
				<td>{{ $delivery->days }} days</td>
				<td>{{ $delivery->price }}</td>
				<td>{{ $delivery->name }} - {{ $delivery->days }} day(s) - {{ $delivery->price }}</td>
				<td>
					<form action="{{ route('post.deletedelivery', ['section' => $section, 'delivery' => $delivery->id, 'product' => $product->id]) }}" method="post">
						@csrf
						<button type="submit" class="text-danger">delete</button>
					</form>
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="5">The product has no delivery methods!</td>
			</tr>
			@endforelse
		@else
			@forelse($deliveries as $delivery)
			<tr>
				<td>{{ $delivery['name'] }}</td>
				<td>{{ $delivery['days'] }}</td>
				<td>{{ $delivery['price'] }}</td>
				<td>{{ $delivery['name'] }} - {{ $delivery['days'] }} day(s) - {{ $delivery['price'] }}</td>
				<td>
					<form action="{{ route('post.deletedelivery', ['section' => 'add', 'delivery' => $delivery['uuid']]) }}" method="post">
						@csrf
						<button type="submit" class="text-danger">delete</button>
					</form>
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="5">The product has no delivery methods!</td>
			</tr>
			@endforelse
		@endif
	</tbody>
</table>
<div class="container mt-20">
	<form action="{{ $section == 'edit' ? route('post.delivery', ['section' => $section, 'product' => $product->id]) : route('post.delivery', ['section' => 'add']) }}" method="post">
		@csrf
		<div class="form-group">
			<div class="label">
				<label for="name">name</label>
			</div>
			<input type="text" id="name" name="name" placeholder="ex. world wide" maxlength="10">
			@error('name')
			<div class="error">
				<small class="text-danger">{{ $errors->first('name') }}</small>
			</div>
			@enderror
		</div>
		<div class="form-group">
			<div class="label">
				<label for="days">days</label>
			</div>
			<input type="text" id="days" name="days" placeholder="max 30 days" maxlength="2">
			@error('days')
			<div class="error">
				<small class="text-danger">{{ $errors->first('days') }}</small>
			</div>
			@enderror
		</div>
		<div class="form-group">
			<div class="label">
				<label for="price">price</label>
			</div>
			<input type="text" id="price" name="price" placeholder="max $999.999" maxlength="6">
			@error('price')
			<div class="error">
				<small class="text-danger">{{ $errors->first('price') }}</small>
			</div>
			@enderror
		</div>
		<button type="submit">add delivery method</button>
	</form>
</div>
<a href="{{ $section == 'edit' ? route('offers', ['section' => $section, 'product' => $product->id]) : route('offers', ['section' => 'add']) }}" class="h3">back</a>
<a href="{{ $section == 'edit' ? route('informations', ['section' => $section, 'product' => $product->id]) : route('informations', ['section' => 'add']) }}" class="h3 float-right">next step</a>

@stop
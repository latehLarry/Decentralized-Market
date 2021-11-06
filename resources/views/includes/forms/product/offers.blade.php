@extends('product')

@section('product-form')

<div class="h3 mt-10">Product offers</div>
<table class="zebra table-space">
	<thead>
		<tr>
			<th>quantity</th>
			<th>price(in USD)</th>
			<th>mesure</th>
			<th>preview</th>
			<th>#</th>
		</tr>
	</thead>
	<tbody>
		@if($section == 'edit')
			@forelse($product->offers as $offer)
			<tr>
				<td>{{ $offer->quantity }}</td>
				<td>{{ $offer->price }}</td>
				<td>{{ $offer->mesure }}</td>
				<td>{{ $offer->quantity }} {{ $offer->mesure }} per {{ $offer->price }}</td>
				<td>
					<form action="{{ route('post.deleteoffer', ['section' => $section, 'offer' => $offer->id, 'product' => $product->id]) }}" method="post">
						@csrf
						<button type="submit" class="text-danger">delete</button>
					</form>
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="5">The product has no offer!</td>
			</tr>
			@endforelse
		@else
			@forelse($offers as $offer)
			<tr>
				<td>{{ $offer['quantity'] }}</td>
				<td>{{ $offer['price'] }}</td>
				<td>{{ $offer['mesure'] }}</td>
				<td>{{ $offer['quantity'] }} {{ $offer['mesure'] }} per {{ $offer['price'] }}</td>
				<td>
					<form action="{{ route('post.deleteoffer', ['section' => 'add', 'offer' => $offer['uuid']]) }}" method="POST">
						@csrf
						<button type="submit" class="text-danger">delete</button>
					</form>
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="5">The product has no offer!</td>
			</tr>
			@endforelse
		@endif
	</tbody>
</table>
<div class="container mt-20">
	<form action="{{ $section == 'edit' ? route('post.offer', ['section' => $section, 'product' => $product->id]) : route('post.offer', ['section' => 'add']) }}" method="POST">
		@csrf
		<div class="form-group">
			<div class="label">
				<label for="quantity">quantity</label>
			</div>
			<input type="text" id="quantity" name="quantity" placeholder="max 999.999" maxlength="6">
			@error('quantity')
			<div class="error">
				<small class="text-danger">{{ $errors->first('quantity') }}</small>
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
		<div class="form-group">
			<div class="label">
				<label for="mesure">mesure</label>
			</div>
			<input type="text" id="mesure" name="mesure" placeholder="ex. grams" maxlength="10">
			@error('mesure')
			<div class="error">
				<small class="text-danger">{{ $errors->first('mesure') }}</small>
			</div>
			@enderror
		</div>
		<button type="submit">add offer</button>
	</form>
</div>
<a href="{{ $section == 'edit' ? route('images', ['section' => $section, 'product' => $product->id]) : route('images', ['section' => 'add']) }}" class="h3">back</a>
<a href="{{ $section == 'edit' ? route('deliveries', ['section' => $section, 'product' => $product->id]) : route('deliveries', ['section' => 'add']) }}" class="h3 float-right">next step</a>

@stop
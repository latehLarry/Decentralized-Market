@extends('master.main')

@section('title', "Shopping cart ($totalProducts)")

@section('content')

<div class="content-master">
	@include('includes.flash.success')
	@include('includes.flash.error')
	<div class="h2 mb-20 inblock">Shopping cart ({{ $totalProducts }})</div> 
	<div class="clear float-right inblock">
		<form action="{{ route('post.clearcart') }}" method="post">
			@csrf
			<button>clear cart</button>
		</form>
	</div>
	<table class="zebra table-space">
		<thead>
			<tr>
				<th>product</th>
				<th>seller</th>
				<th>quantity</th>
				<th>price</th>
				<th>delivery</th>
				<th>sub-total</th>
				<th>#</th>
			</tr>
		</thead>
		<tbody>
			@forelse($products as $product)
				<tr>
					<td><a href="{{ route('product', ['product' => $product['product_id'] ]) }}">{{ $product['product_name'] }}</a></td>
					<td><a href="{{ route('seller', ['seller' => $product['seller'] ]) }}">{{ $product['seller'] }}</a></td>
					<td>{{ $product['quantity'] }}</td>
					<td>@include('includes.components.displayprice', ['price' => $product['price']])</td>
					<td>{{ $product['delivery_method'] }} - @include('includes.components.displayprice', ['price' => $product['delivery_price']])</td>
					<td>@include('includes.components.displayprice', ['price' => $product['total']])</td>
					<td>
						<form action="{{ route('post.removetocart', ['product' => $product['product_id'] ]) }}" method="post">
							@csrf
							<button type="submit" class="btn-link">remove</button>
						</form>
					</td>
				</tr>
			@empty
				<tr>
					<td colspan="7">Your shopping cart is empty!</td>
				</tr>
			@endforelse
		</tbody>
	</table>
	<div class="container mt-20 inblock">
		<div style="font-weight: bold; font-size: 25px">Total: @include('includes.components.displayprice', ['price' => $totalPrice])</div>
		<div class="footnote mb-15">approximately {{ \App\Tools\Converter::moneroConverter($totalPrice) }} XMR</div>
		<div class="info-wrapper float-right">
			<div class="info-folder">
				<div class="info-icon">?</div>
				<div class="info-message">Your address will be encrypted with the sellers' PGP key, ensuring that only they will have access to your information! See how we protect your identity in the <a href="{{ config('general.wiki_link') }}" target="_blank"><strong>buyer's guide</strong></a>.</div>
			</div>
		</div>
		<form action="{{ route('post.checkout') }}" method="post">
			@csrf
			<div class="label">
				<label for="address">Your name and address</label>
			</div>
			<textarea id="address" name="address" cols="35" rows="10"></textarea>
			@error('address')
			<div class="error">
				<small class="text-danger">{{ $errors->first('address') }}</small>
			</div>
			@enderror
			<div class="mt-10">
				<label for="pin">PIN:</label>
				<input type="password" id="pin" name="pin" maxlength="6">
				@error('pin')
				<div class="error">
					<small class="text-danger">{{ $errors->first('pin') }}</small>
				</div>
				@enderror
			</div>	
			<div class="mt-10 float-right">
				<button type="submit">checkout</button>		
			</div>
		</form>
	</div>
</div>

@stop

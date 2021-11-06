@extends('master.main')

@section('title', 'Staff products')

@section('content')

@include('includes.components.menustaff')
<div class="content-profile">
	@include('includes.flash.success')
	<div class="h3">All products ({{ $totalProducts }})</div>
	<div class="mt-10">
		<form action="{{ route('staff.products', ['product' => $productId, 'seller' => $sellerUsername]) }}">
			<div class="inblock">
				<label for="product_id">product id</label>
				<input type="text" id="product_id" name="product_id" value="{{ $productId }}">
			</div>
			<div class="inblock">
				<label for="seller_username">seller</label>
				<input type="text" id="seller_username" name="seller_username" value="{{ $sellerUsername }}">
			</div>
			<div class="inblock">
				<button type="submit">filter</button>
			</div>
		</form>
	</div>
	<table class="zebra mt-10" style="text-align: center; width: 760px">
		<thead>
			<th>featured image</th>
			<th>category</th>
			<th>product</th>
			<th>from</th>
			<th>ships to</th>
			<th>ships from</th>
			<th>seller</th>
			<th>#</th>
		</thead>
		<tbody>
			@forelse($products as $product)
			<tr>
				<td><img src="{{ $product->featuredImage() }}" height="32px" width="32px"></td>
				<td><a href="{{ route('category', ['slug' => $product->category->slug]) }}">{{ $product->category->name }}</a></td>
				<td><a href="{{ route('product', ['product' => $product->id]) }}">{{ $product->name }}</a></td>
				<td>@include('includes.components.displayprice', ['price' => $product->from()])</td>
				<td>{{ $product->shipsTo() }}</td>
				<td>{{ $product->shipsFrom() }}</td>
				<td>{{ $product->seller->username }}</td>
				<td>
					<div class="inblock mb-10">
						<form action="{{ route('put.staff.featuredproduct', ['product' => $product->id]) }}" method="post">
							@csrf
							@method('PUT')
							<button type="submit">@if($product->featured) remove @endif highlight</button>
						</form>
					</div>
					<div class="inblock mb-10">
						<button><a href="{{ route('images', ['section' => 'edit', 'product' => $product->id]) }}" class="button">edit</a></button>
					</div>
					<div class="inblock">
						<form action="{{ route('post.deleteproduct', ['product' => $product->id]) }}" method="post">
							@csrf
							<button type="submit" style="color: red">delete</button>
						</form>
					</div>
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="8">It looks like there are no products!</td>
			</tr>
			@endforelse
			<tr>
				<td colspan="8">{{ $products->appends($filters)->links('includes.components.pagination') }}</td>
			</tr>
		</tbody>
	</table>
</div>

@stop
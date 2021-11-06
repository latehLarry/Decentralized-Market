<div class="featured-product" title="{{ $product->name }}">
	<a href="{{ route('product', ['product' => $product->id]) }}">
		<img src="{{ $product->featuredImage() }}" class="product-image" width="180" height="135">
		<div class="featured-product-title">{{ $product->name }}</div>
	</a>
	<div class="price-small"><small>from</small> @include('includes.components.displayprice', ['price' => $product->from()])</div>
</div>
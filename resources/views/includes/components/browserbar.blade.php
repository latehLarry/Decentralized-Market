@if($section == 'edit')
<div class="mb-10">
	<form action="{{ route('post.deleteproduct', ['product' => $product->id]) }}" method="post">
		@csrf
		<button type="submit" class="text-danger">delete this product</button>
	</form>
</div>
@endif
<div class="container">
	<a href="{{ $section == 'edit' ? route('images', ['section' => $section, 'product' => $product->id]) : route('images', ['section' => 'add']) }}" class="inblock">images</a> &rarr;
	<a href="{{ $section == 'edit' ? route('offers', ['section' => $section, 'product' => $product->id]) : route('offers', ['section' => 'add']) }}" class="inblock">offers</a> &rarr;
	<a href="{{ $section == 'edit' ? route('deliveries', ['section' => $section, 'product' => $product->id]) : route('deliveries', ['section' => 'add']) }}" class="inblock">deliveries</a> &rarr;
	<a href="{{ $section == 'edit' ? route('informations', ['section' => $section, 'product' => $product->id]) : route('informations', ['section' => 'add']) }}" class="inblock">basic informations</a>
</div>

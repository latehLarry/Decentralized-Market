@extends('master.main')

@section('title', 'Result')

@section('content')

<div class="content-browsing">
	@include('includes.components.filters')
	<div class="h3 mb-10 mt-20">Search result</div>
	@forelse($products as $product)
		@include('includes.components.product.row', ['product' => $product])
	@empty
    	<div class="h3 mt-20" style="text-align: center">Hmm... We don't seem to find any results...</div>
	@endforelse
	{{ $products->appends($filters)->links('includes.components.pagination') }}
</div>

@stop
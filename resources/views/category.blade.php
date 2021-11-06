@extends('master.main')

@section('title', $category->name.' category')

@section('content')

<div class="content-browsing">
	@include('includes.components.filters')
	<div class="h3 mb-10 mt-20">Browsing {{ $category->name }}</div>
    @forelse($products as $product)
    	@include('includes.components.product.row', ['product' => $product])
    @empty
    	<div class="h3 mt-20" style="text-align: center">This category has no products!</div>
    @endforelse

    {{ $products->links('includes.components.pagination') }}
</div>

@stop
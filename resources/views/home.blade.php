@extends('master.main')

@section('title', 'Home')

@section('content')

<div class="content-sidebar">
	@include('includes.components.notices')
	@include('includes.components.yoursellers')
</div>
<div class="content-homepage">
	@foreach($featuredProducts as $featuredProduct)
		@include('includes.components.product.featured', ['product' => $featuredProduct])
	@endforeach
	<div class="rodape"></div>
</div>

@stop
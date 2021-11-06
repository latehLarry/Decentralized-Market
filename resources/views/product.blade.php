@extends('master.main')

@section('title', 'Product')

@section('content')

<div class="content-master">
	@include('includes.flash.success')
	@include('includes.flash.error')
	<div class="h2 mb-15">{{ $section != 'add' ? 'Edit product' : 'Add new product' }}</div>
	@include('includes.components.browserbar')
	@yield('product-form')
</div>

@stop
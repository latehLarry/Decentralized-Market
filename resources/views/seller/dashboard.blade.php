@extends('master.main')

@section('title', 'Seller dashboard')

@section('content')

@include('includes.components.menuaccount')
<div class="content-profile">
	@include('includes.flash.success')
	<div class="h2 mb-15">Seller dashboard</div>
	<div class="flashdata flashdata-error mb-10">Your description and rules must have a maximum of 10000 characters. Markdown is supported!</div>
	<form action="{{ route('put.seller.dashboard') }}" method="post">
		@csrf
		@method('PUT')
		<div class="form-group">
			<div class="label">
				<label for="description">my description</label>
			</div>
			<textarea id="description" name="description" rows="18" style="width: 98.8%">{{ $seller->seller_description }}</textarea>
			@error('description')
			<div class="error">
				<small class="text-danger">{{ $errors->first('description') }}</small>
			</div>
			@enderror
		</div>
		<div class="form-group mt-10">
			<div class="label">
				<label for="rules">my rules</label>
			</div>
			<textarea id="rules" name="rules" rows="18" style="width: 98.8%">{{ $seller->seller_rules }}</textarea>
			@error('rules')
			<div class="error">
				<small class="text-danger">{{ $errors->first('rules') }}</small>
			</div>
			@enderror
		</div>
		<button type="submit">save profile</button>
	</form>
	<div class="float-right mt-20">
		<a href="{{ route('images', ['section' => 'add']) }}"><div class="h3">add product</div></a>
	</div>
	<table class="zebra table-space mt-20">
		<thead>
			<tr>
				<th>featured image</th>
				<th>product name</th>
				<th>from</th>
				<th>ships to</th>
				<th>#</th>
			</tr>
		</thead>
		<tbody>
			@forelse($products as $product)
			<tr>
				<td><img src="{{ $product->featuredImage() }}" width="32px" height="32px"></td>
				<td><a href="{{ route('product', ['product' => $product->id]) }}">{{ $product->name }}</a></td>
				<td>@include('includes.components.displayprice', ['price' => $product->from()])</td>
				<td>{{ $product->shipsTo() }}</td>
				<td>
					<div class="inblock">
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
				<td colspan="5">You don't have any products!</td>
			</tr>
			@endforelse
			<tr>
				<td colspan="5">{{ $products->links('includes.components.pagination') }}</td>
			</tr>
		</tbody>
	</table>
</div>

@stop
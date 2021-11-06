@extends('product')

@section('product-form')

<div class="flashdata flashdata-error mt-15 mb-15">The product description and refund policy must be a maximum of 5000 characters. Markdown is supported!</div>
<div class="h3">Basic product information</div>
<div class="container">
	<form action="{{ $section == 'edit' ? route('post.informations', ['section' => $section, 'product' => $product->id]) : route('post.informations', ['section' => 'add']) }}" method="post">
		@csrf
		<div class="form-group inblock">
			<div class="label">
				<label for="name">product name</label>
			</div>
			<input type="text" id="name" name="name" maxlength="50" @if($section == 'edit') value="{{ $product->name }}" @endif>
			@error('name')
			<div class="error inblock">
				<small class="text-danger">{{ $errors->first('name') }}</small>
			</div>
			@enderror
		</div>
		<div class="form-grou inblock">
			<div class="label">
				<label for="category">category</label>
			</div>
			<select id="category" name="category" class="dropdown-wrapper">
				@foreach($categories as $category)
				<option value="{{ $category->id }}" @if($section == 'edit' and $product->category_id == $category->id) selected @endif>{{ $category->name }}</option>
				@endforeach
			</select>
			@error('category')
			<div class="error inblock">
				<small class="text-danger">{{ $errors->first('category') }}</small>
			</div>
			@enderror
		</div>
		<div class="form-group inblock">
			<div class="label">
				<label for="ships_from">ships from</label>
			</div>
			<select id="ships_from" name="ships_from" class="dropdown-wrapper">
				@foreach(config('countries') as $key => $shipsFrom)
				<option value="{{ $key }}" @if($section == 'edit' and $product->ships_from == $key) selected @endif>{{ $shipsFrom }}</option>
				@endforeach
			</select>
			@error('ships_from')
			<div class="error inblock">
				<small class="text-danger">{{ $errors->first('ships_from') }}</small>
			</div>
			@enderror
		</div>
		<div class="form-group inblock">
			<div class="label">
				<label for="ships_to">ships to</label>
			</div>
			<select id="ships_to" name="ships_to" class="dropdown-wrapper">
				@foreach(config('countries') as $key => $shipsTo)
				<option value="{{ $key }}" @if($section == 'edit' and $product->ships_to == $key) selected @endif>{{ $shipsTo }}</option>
				@endforeach
			</select>
			@error('ships_to')
			<div class="error inblock">
				<small class="text-danger">{{ $errors->first('ships_to') }}</small>
			</div>
			@enderror
		</div>
		<div class="form-group">
			<div class="label">
				<label for="description">description</label>
			</div>
			<textarea id="description" name="description" cols="60" rows="15">@if($section == 'edit') {{ $product->description }} @endif</textarea>
			@error('description')
			<div class="error">
				<small class="text-danger">{{ $errors->first('description') }}</small>
			</div>
			@enderror
		</div>
		<div class="form-group">
			<div class="label">
				<label for="refund_policy">refund policy</label>
			</div>
			<textarea id="refund_policy" name="refund_policy" cols="60" rows="15">@if($section == 'edit') {{ $product->refund_policy }} @endif</textarea>
			@error('refund_policy')
			<div class="error">
				<small class="text-danger">{{ $errors->first('refund_policy') }}</small>
			</div>
			@enderror
		</div>
		<button type="submit">save product</button>
	</form>
</div>
<a href="{{ $section == 'edit' ? route('deliveries', ['section' => $section, 'product' => $product->id]) : route('deliveries', ['section' => 'add']) }}" class="h3">back</a>

@stop
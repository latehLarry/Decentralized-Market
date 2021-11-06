@extends('product')

@section('product-form')

<div class="h3 mt-20">Product images</div>
<div class="images-grid">
	@if($section == 'edit')
		@forelse($product->images as $image)
		<div class="inblock mt-10">
			<img src="{{ $image->image }}" width="128px" height="128px">
			<form action="{{ route('post.deleteimage', ['section' => $section, 'image' => $image->id, 'product' => $product->id]) }}" method="post">
				@csrf
				<button type="submit" class="text-danger">delete</button>
			</form>
		</div>
		@empty
		<div class="mt-10">You haven't added any images yet...</div>
		@endforelse
	@else
		@forelse($images as $image)
		<div class="inblock mt-10">
			<img src="{{ $image['image'] }}" width="128px" height="128px">
			<form action="{{ route('post.deleteimage', ['section' => 'add', 'image' => $image['uuid']]) }}" method="post">
				@csrf
				<button type="submit" class="text-danger">delete</button>
			</form>
		</div>
		@empty
		<div class="mt-10">You haven't added any images yet...</div>
		@endforelse	
	@endif
</div>
<div class="container mt-20">
	<form action="{{ $section == 'edit' ? route('post.image', ['section' => $section, 'product' => $product->id]) : route('post.image', ['section' => 'add']) }}" method="post" enctype="multipart/form-data">
		@csrf
		<label for="new_image">new image</label>
		<input type="file" id="new_image" name="new_image">
			@error('new_image')
		<div class="text-danger">
			<small class="text-danger">{{ $errors->first('new_image') }}</small>
		</div>
		@enderror
		<div class="mt-10">
			<button type="submit">add image</button>
		</div>
	</form>
</div>
<a href="{{ $section == 'edit' ? route('offers', ['section' => $section, 'product' => $product->id]) : route('offers', ['section' => 'add']) }}" class="h3 float-right">next step</a>

@stop
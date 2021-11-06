@extends('master.main')

@section('title', 'Admin categories')

@section('content')

@include('includes.components.menustaff')

<div class="content-profile">
	@include('includes.flash.validation')
	@include('includes.flash.success')
	@include('includes.flash.error')
	<div class="h3">Add new category</div>
	<div class="container footnote mt-10">
		<div class="h3">Comments</div>
		<ul>
			<li>The category name must be unique.</li>
			<li>You can only delete a category if it doesn't have any items.</li>
			<li>The slug is by default named after the category.</li>
		</ul>
	</div>
	<form action="{{ route('post.admin.addcategories') }}" method="post" class="mt-10">
		@csrf
		<input type="text" id="name" name="name">
		<select id="parent_category" name="parent_category" class="dropdown-wrapper">
			@foreach($allCategories as $category)
			<option value="{{ $category->id }}">{{ $category->name }}</option>
			@endforeach
		</select>
		<button type="submit">create category</button>
	</form>
	<ul>
	@forelse($rootsCategories as $category)
		<li><a href="{{ route('admin.category', ['category' => $category->id]) }}">{{ $category->name }}</a> <span class="footnote">{{ $category->totalProducts() }}</span>&nbsp;&nbsp;&nbsp;<a href="{{ route('delete.admin.category', ['category' => $category->id]) }}" class="link-danger">delete</a></li>
		@if(!empty($category->subcategories))
			@include('includes.components.subcategorieslist', ['subcategories' => $category->subcategories])
		@endif
	@empty
		<div class="h3">Hmm... It seems that the market still doesn't have any categories!</div>
	@endforelse
	</ul>
</div>

@stop
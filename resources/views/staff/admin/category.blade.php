@extends('master.main')

@section('title', 'Admin edit category')

@section('content')

@include('includes.components.menustaff')

<div class="content-profile">
	@include('includes.flash.validation')
	@include('includes.flash.success')
	@include('includes.flash.error')
	<div class="h3">Edit {{ $category->name }} category</div>
	<div class="container footnote mt-10">
		<div class="h3">Comments</div>
		<ul>
			<li>The category name must be unique.</li>
			<li>You can only delete a category if it doesn't have any items.</li>
			<li>The slug is by default named after the category.</li>
		</ul>
	</div>
	<form action="{{ route('put.admin.editcategory', ['category' => $category->id]) }}" method="post" class="mt-10">
		@csrf
		@method('PUT')
		<input type="text" id="name" name="name" value="{{ $category->name }}">
		<select id="parent_category" name="parent_category" class="dropdown-wrapper">
			<option value="">None</option>
			@foreach($allCategories as $cat)
			<option value="{{ $cat->id }}" @if($cat->id == $category->parent_category) selected @endif>{{ $cat->name }}</option>
			@endforeach
		</select>
		<div class="inblock">
			<button><a href="{{ route('admin.categories') }}" class="button">back</a></button>
		</div>
		<button type="submit">edit {{ $category->name }}</button>
	</form>
	<ul>
</div>

@stop
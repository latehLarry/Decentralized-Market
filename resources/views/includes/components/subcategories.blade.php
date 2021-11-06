@foreach($subcategories as $subcategory)
	<div class="subcategory-link">
		<a href="{{ route('category', ['slug' => $subcategory->slug]) }}">{{ $subcategory->name }}</a><span class="footnote"> {{ $subcategory->totalProducts() }}</span>
		@if($subcategory->isParent())
			@include('includes.components.subcategories', ['subcategories' => $subcategory->subcategories])
		@endif
	</div>
@endforeach
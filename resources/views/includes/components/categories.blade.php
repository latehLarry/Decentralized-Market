@php

#Get all root categories
$categories = \App\Models\Category::roots();

@endphp

<div class="categories">
   <div class="categories-folder">
      <div class="categories-header">
         <span style="font-weight: normal">Shop by</span> Category
      </div>
      <div class="categories-body">
         @foreach($categories as $category)
            <div class="category-link">
               <a href="{{ route('category', ['slug' => $category->slug]) }}">{{ $category->name }}</a><span class="footnote"> {{ $category->totalProducts() }}</span>
               @if($category->isParent())
                  @include('includes.components.subcategories', ['subcategories' => $category->subcategories])
               @endif
            </div>
         @endforeach
      </div>
   </div>
</div>
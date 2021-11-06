@extends('master.main')

@section('title', 'Favorites')

@section('content')

<div class="content-browsing">
	<div class="h3 mb-10">Favorite listings</div>
    @forelse($favorites as $favorite)
    	@include('includes.components.product.row', ['product' => $favorite->product])
    @empty
    	<div class="h3 mt-20" style="text-align: center">Looks like you don't have any products in your favorites yet!</div>
    @endforelse
    {{ $favorites->links('includes.components.pagination') }}
</div>

@stop
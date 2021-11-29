@extends('master.main')

@section('title', $product->name)

@section('content')

<div class="content-master">
	@include('includes.flash.validation')
	@include('includes.flash.success')
	@include('includes.flash.error')
	<div class="inblock">
		<div class="carrousel">
			@foreach($product->images as $index => $image)
			<div id="image{{ $index }}" class="slide">
				<img src="{{ $image->image }}" width="400px" height="300px">
			</div>
			@endforeach
		</div>
		<div class="mt-10">
			@foreach($product->images as $index => $image)
			<div class="inblock">
				<a href="#image{{ $index }}"><img src="{{ $image->image }}" alt="{{ $product->name }}" width="72px" height="72px"></a>
			</div>
			@endforeach
		</div>
	</div>
	<div class="product-alldetails inblock" style="width: 538px">
		<div class="h1">{{ $product->name }}</div>
		<div class="mt-20">
			<span class="price-big"><small>from</small> @include('includes.components.displayprice', ['price' => $product->from()])</span>&ensp;&ensp;<a href="{{ route('report', ['product' => $product->id]) }}" target="_blank">report this product</a>
		</div>
		<div class="container mt-20">
			seller: <a href="{{ route('seller', ['seller' => $product->seller->username]) }}">{{ $product->seller->username }}({{ $product->seller->totalFeedbacks() }})</a><br>
			ships from: {{ $product->shipsFrom() }}<br>
			ships to: {{ $product->shipsTo() }}<br>
			finalize early (present): <strong>{{ $product->seller->finalizeEarly() == true ? 'yes' : 'no' }}</strong><br>
			category: @foreach($product->category->parents() as $pc) <a href="{{ route('category', ['slug' => $pc->slug]) }}">{{ $pc->name }}</a> &rarr; @endforeach <a href="{{ route('category', ['slug' => $product->category->slug]) }}">{{ $product->category->name }}</a>
			<form action="{{ route('post.favorites', ['product' => $product->id]) }}" method="post" class="mt-10">
				@csrf
				<button type="submit">{{ auth()->user()->isFavorite($product) ? 'remove' : 'add' }} to favorites</button>
			</form>
		</div>
		<div class="container mt-10">
			<form action="{{ route('post.addtocart', ['product' => $product->id]) }}" method="post">
				@csrf
				<div class="form-group">
					<label for="offer">offers:</label>
					<select id="offer" name="offer" class="dropdown-wrapper">
						@foreach($product->offers as $offer)
						<option value="{{ $offer->id }}">{{ $offer->quantity }} {{ $offer->mesure }} per @include('includes.components.displayprice', ['price' => $offer->price])</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="delivery">delivery method:</label>
					<select id="delivery_method" name="delivery_method" class="dropdown-wrapper">
						@foreach($product->deliveries as $delivery)
						<option value="{{ $delivery->id }}">{{ $delivery->name }} - {{ $delivery->days }} day(s) - @include('includes.components.displayprice', ['price' => $delivery->price])</option>
						@endforeach
					</select>
				</div>
				<div class="mt-10 inblock">
					<button type="submit">add to cart</button>
				</div>
				@can('update-product', $product)
				<div class="inblock">
					<button><a href="{{ route('images', ['section' => 'edit', 'product' => $product->id]) }}" class="button">edit this product</a></button>
				</div>
				@endcan
			</form>
		</div>
	</div>
	<div class="container mt-40">
		<div class="h3">Description</div>
		{!! Illuminate\Support\Str::markdown(strip_tags($product->description)) !!}
	</div>
	<div class="container mt-40">
		<div class="h3">Refund policy</div>
		{!! Illuminate\Support\Str::markdown(strip_tags($product->refund_policy)) !!}
	</div>
	<div class="h3 mt-40">Customer reviews</div>
	<table class="zebra table-space mt-10">
		<thead>
			<th>rating</th>
			<th>type</th>
			<th>user</th>
			<th>review</th>
			<th>freshness</th>
		</thead>
		<tbody>
			@forelse($feedbacks as $feedback)
			<tr>
				<td>{{ number_format($feedback->rating, 2) }} of 5</td>
				<td>{{ $feedback->type }}</td>
				<td>{{ $feedback->hiddenUser() }}</td>
				<td style="text-align: left">{{ $feedback->message }}</td>
				<td>{{ $feedback->freshness() }}</td>
			</tr>
			@empty
			<tr>
				<td colspan="5">Humm... Looks like this product doesn't have any reviews yet.</td>
			</tr>
			@endforelse
			<tr>
				<td colspan="5">{{ $feedbacks->links('includes.components.pagination') }}</td>
			</tr>
		</tbody>
	</table>
</div>

@stop
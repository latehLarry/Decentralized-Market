@extends('master.main')

@section('title', 'Staff disputes')

@section('content')

@include('includes.components.menustaff')
<div class="content-profile">
	@include('includes.flash.success')
	@include('includes.flash.error')
	<div class="h3">All disputes ({{ $disputes->count() }})</div>
	<table class="zebra mt-10" style="width: 100%; text-align: center">
		<thead>
			<tr>
				<th>product</th>
				<th>seller</th>
				<th>buyer</th>
				<th>winner</th>
				<th>UUID</th>
			</tr>
		</thead>
		<tbody>
			@forelse($disputes as $dispute)
			<tr>
				<td><a href="{{ route('product', ['product' => $dispute->product->id]) }}">{{ $dispute->product->name }}</a></td>
				<td><a href="{{ route('seller', ['seller' => $dispute->seller->username]) }}">{{ $dispute->seller->username }}</a></td>
				<td>{{ $dispute->buyer->username }}</td>
				<td>{{ $dispute->winner != null ? $dispute->winner->username : 'undefined' }}</td>
				<td><a href="{{ route('order', ['order' => $dispute->order->id]) }}">{{ $dispute->order->id }}</a></td>
			</tr>
			@empty
			<tr>
				<td colspan="5">The market still has no disputes!</td>
			</tr>
			@endforelse
			<tr>
				<td colspan="5">{{ $disputes->links('includes.components.pagination') }}</td>
			</tr>
		</tbody>
	</table>
</div>

@stop
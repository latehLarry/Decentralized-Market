@extends('master.main')

@section('title', 'Orders')

@section('content')

<div class="content-master">
	<div class="h2 mb-15">Orders</div>
	<a href="{{ route('orders', ['status' => 'all']) }}" class="container">all <span class="nav-count">{{ $user->orders->count() }}</span></a>
	<a href="{{ route('orders', ['status' => 'waiting']) }}" class="container">waiting  <span class="nav-count">{{ $user->totalOrders('waiting') }}</span></a>
	<a href="{{ route('orders', ['status' => 'accepted']) }}" class="container">accepted  <span class="nav-count">{{ $user->totalOrders('accepted') }}</span></a>
	<a href="{{ route('orders', ['status' => 'shipped']) }}" class="container">shipped  <span class="nav-count">{{ $user->totalOrders('shipped') }}</span></a>
	<a href="{{ route('orders', ['status' => 'delivered']) }}" class="container">delivered  <span class="nav-count">{{ $user->totalOrders('delivered') }}</span></a>
	<a href="{{ route('orders', ['status' => 'canceled']) }}" class="container">canceled  <span class="nav-count">{{ $user->totalOrders('canceled') }}</span></a>
	<a href="{{ route('orders', ['status' => 'disputed']) }}" class="container">disputed  <span class="nav-count">{{ $user->totalOrders('disputed') }}</span></a>
	<table class="zebra table-space mt-20">
		<thead>
			<tr>
				<th>product</th>
				<th>seller</th>
				<th>finalize early</th>
				<th>total</th>
				<th>status</th>
				<th>paid</th>
				<th>UUID</th>
			</tr>
		</thead>
		<tbody>
			@forelse($orders as $order)
				<tr>
					<td><a href="{{ route('product', ['product' => $order->product->id ]) }}">{{ $order->product->name }}</a></td>
					<td><a href="{{ route('seller', ['seller' => $order->seller->username]) }}">{{ $order->seller->username }}</a></td>
					<td>{{ $order->seller->fe == true ? 'yes' : 'no' }}</td>
					<td>@include('includes.components.displayprice', ['price' => $order->total])</td>
					<td><strong>{{ $order->status }}</strong></td>
					<td><span class="flashdata flashdata-warning">{{ $order->paidOrder() ? 'yes' : 'no' }}</span></td>
					<td><a href="{{ route('order', ['order' => $order->id]) }}">{{ $order->id }}</a></td>
				</tr>
			@empty
				<tr>
					<td colspan="7">Hmm... Looks like you don't have any orders yet.</td>
				</tr>
			@endforelse
				<tr>
					<td colspan="7">{{ $orders->links('includes.components.pagination') }}</td>
				</tr>
		</tbody>
	</table>
</div>

@stop
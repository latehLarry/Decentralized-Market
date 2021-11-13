@extends('master.main')

@section('title', 'Order #'.$order->id)

@section('content')

<div class="content-browsing">
	@include('includes.flash.validation')
	@include('includes.flash.success')
	@include('includes.flash.error')
	<div class="h2 mb-15">Order - <a href="{{ route('product', ['product' => $order->product_id]) }}">{{ $order->product->name }}</a></div>
	<div class="title">
		<div class="h3 inblock" style="width: 60%">Buyer statistics - {{ $order->buyer->username }}</div>
		<div class="h3 inblock">Order details</div>
	</div>
	<div class="inblock" style="vertical-align: top">
		<div class="avatar">
			<img src="{{ $order->buyer->avatar }}" height="96px" width="96px" class="product-image">
		</div>
		<div class="">
			@if(!$order->isBuyer())
				<a href="{{ route('conversations', ['user' => $order->buyer->username]) }}">send message</a>
			@endif
		</div>
	</div>
	<div class="inblock" style="width: 50%">
		@include('includes.components.buyerstats', ['user' => $user])
	</div>
	<div class="inblock" style="vertical-align: top">
		<table class="zebra">
			<tr style="text-align: left">
				<th colspan="2">UUID: {{ $order->id }}</th>
			</tr>
			<tr>
				<td>quantity</td>
				<td>{{ $order->quantity }}</td>
			</tr>
			<tr>
				<td>total price</td>
				<td>{{ \App\Tools\Converter::currencyConverter($order->total, auth()->user()->currency) }} {{ auth()->user()->currency }}</td>
			</tr>
			<tr>
				<td>delivery method</td>
				<td>{{ $order->delivery_method }}</td>
			</tr>
		</table>
	</div>
	<div class="col mt-40">
		<div class="title">
			<div class="h3 inblock" style="width:63%">Order cycle</div>
			<div class="h3 inblock">Delivery address</div>
		</div>
		<div class="inblock" style="vertical-align: top; width: 606px">
			<table class="zebra" style="width: 100%">
				<tr>
					<thead>
						<th style="text-align: left">payment wallet
							@if($order->isBuyer())
							<div class="info-wrapper float-right">
								<div class="info-folder">
									<div class="info-icon">?</div>
									<div class="info-message">Send the amount informed to this address.</div>
								</div>
							</div>
							@endif			
						</th>
						<th>total sent</th>
						<th>to pay</th>
						<th>paid</th>
					</thead>
				</tr>
				<tbody>
					<tr>
						<td><input type="text" value="{{ $order->escrow_monero_wallet }}" style="font-family: Courier; font-size: 90%; background-color: #fff; color: #000; font-weight: bold; width: 261px" disabled></td>
						<td class="text-center">&#8499;{{ $totalSent }}</td>
						<td class="text-center">&#8499;{{ $toPay }}</td>
						<td><div class="flashdata flashdata-warning">{{ $order->paidOrder() ? 'yes' : 'no' }}</div></td>
					</tr>
				</tbody>
			</table>
			<div class="container inblock mt-10">
				<span class="browser @if($order->waiting()) active @endif">waiting</span> &rarr;
				<span class="browser @if($order->accepted()) active @endif">accepted</span> &rarr;
				<span class="browser @if($order->shipped()) active @endif">shipped</span> &rarr;
				<span class="browser @if($order->delivered()) active @endif">delivered</span> 
			</div>
			<div class="container inblock mt-10">
				<span class="browser @if($order->canceled()) active @endif">canceled</span> 
			</div>
			<div class="container inblock mt-10">
				<span class="browser @if($order->disputed()) active @endif">In dispute</span> 
			</div>
			<div class="footnote container mt-10">
				<div class="h3 mb-10">Note</div>
				@if($order->waiting())
					@if($order->isBuyer())
						<span>Pay for the purchase and wait for the seller to accept your order. If the seller does not accept, your money will be refunded. <strong>Orders with the status "waiting" are automatically canceled within two days.</strong></span>
					@else
						<span>You can only accept the order after the buyer sends the money to the payment wallet. <strong>Orders with the status "waiting" are automatically canceled within two days.</strong></span>	
					@endif
					<div class="actions mt-20">
						@if(!$order->isBuyer())
							<form action="{{ route('post.changeorderstatus', ['order' => $order->id, 'status' => 'accepted']) }}" method="post" class="inblock">
								@csrf
								<button type="submit">accept order</button>
							</form>
						@endif
						<form action="{{ route('post.changeorderstatus', ['order' => $order->id, 'status' => 'canceled']) }}" method="post" class="inblock">
							@csrf
							<button type="submit">cancel order</button>
						</form>
					</div>
				@endif
				@if($order->accepted())
					@if($order->isBuyer())
						<span>Seller has accepted your order!</span>
					@else
						<span>Order marked as accept! Mark as shipped after the product is shipped.</span>	
					@endif
					<div class="actions mt-20">
						@if(!$order->isBuyer())
							<form action="{{ route('post.changeorderstatus', ['order' => $order->id, 'status' => 'shipped']) }}" method="post" class="inblock">
								@csrf
								<button type="submit">order dispatched</button>
							</form>
						@endif
						<form action="{{ route('post.changeorderstatus', ['order' => $order->id, 'status' => 'canceled']) }}" method="post" class="inblock">
							@csrf
							<button type="submit">cancel order</button>
						</form>
						<form action="{{ route('post.changeorderstatus', ['order' => $order->id, 'status' => 'disputed']) }}" method="post" class="inblock">
							@csrf
							<button type="submit">start dispute</button>
						</form>
					</div>
				@endif
				@if($order->shipped())
					<span>The order has been marked as shipped! <strong>Purchases with the status "shipped" complete automatically within thirty days.</strong></span>
					<div class="actions mt-20">
						@if($order->isBuyer())
							<form action="{{ route('post.changeorderstatus', ['order' => $order->id, 'status' => 'delivered']) }}" method="post" class="inblock">
								@csrf
								<button type="submit">order delivered</button>
							</form>
						@endif
						<form action="{{ route('post.changeorderstatus', ['order' => $order->id, 'status' => 'disputed']) }}" method="post" class="inblock">
							@csrf
							<button type="submit">start dispute</button>
						</form>
						@if($order->isBuyer() && $order->seller->finalizeEarly())
						<form action="{{ route('post.finalizearly', ['order' => $order->id]) }}" method="post" class="inblock float-right">
							@csrf
							<button type="submit">finalize early</button>
						</form>
						@endif
					</div>
				@endif
				@if($order->delivered())
					@if($order->isBuyer())
						<span>You marked the order as delivered and the funds have been released to the seller. In case of problems with the received product, please start a dispute.</span>
						<div class="actions mt-20">
							<form action="{{ route('post.changeorderstatus', ['order' => $order->id, 'status' => 'disputed']) }}" method="post" class="inblock">
								@csrf
								<button type="submit">start dispute</button>
							</form>
						</div>
						<form action="{{ route('post.feedback', ['feedback' => $feedback->id]) }}" method="post" class="mt-20 text-default">
							<div class="h3 mb-10">My feedback</div>
							@csrf
							<div class="form-group inblock">
								<div class="label">
									<label for="rating">rating</label>
								</div>
								<input type="text" id="rating" name="rating" placeholder="from 1 to 5" @if(!is_null($feedback)) value="{{ $feedback->rating }}" @endif>
							</div>
							<div class="form-group inblock">
								<div class="label">
									<label for="type">type</label>
								</div>
								<select id="type" name="type" class="dropdown-wrapper">
								@foreach(config('general.feedback_type') as $type)
									<option value="{{ $type }}" @if(!is_null($feedback) and $feedback->type == $type) selected @endif>{{ $type }}</option>
								@endforeach
								</select>
							</div>
							<div class="form-group">
								<div class="label">
									<label for="feedback">feedback</label>
								</div>
								<textarea id="feedback" name="feedback" cols="50" rows="20">@if(!is_null($feedback)) {{ $feedback->message }} @endif</textarea>
							</div>
							<button type="submit">submit feedback</button>
						</form>
					@else
						<span>Order marked as delivered! Funds have been released. Money will drop after two confirmations.</span>
						<div class="actions mt-20">
							<form action="{{ route('post.changeorderstatus', ['order' => $order->id, 'status' => 'disputed']) }}" method="post" class="inblock">
								@csrf
								<button type="submit">start dispute</button>
							</form>
						</div>
					@endif
				@endif
				@if($order->disputed())
					<span>A dispute has started! Use the field below to send messages.</span>
					@if($order->dispute->winner_id != null)
						<span class="flashdata flashdata-success mt-10 mb-10">Winner of the dispute: {{ $order->dispute->winner->username }}</span>
					@else
						<span class="flashdata flashdata-error mt-10 mb-10">Winner of the dispute: none</span>
					@endif
					@staff
					<form action="{{ route('post.resolvedispute', ['dispute' => $order->dispute->id]) }}" method="post" class="mb-15">
						@csrf
						<label id="winner" class="text-default">winner:</label>
						<select id="winner" name="winner" class="dropdown-wrapper">
							<option value="">none</option>
							<option value="{{ $order->buyer->id}}" @if($order->dispute->winner_id == $order->buyer->id) selected @endif>{{ $order->buyer->username }} (buyer)</option>
							<option value="{{ $order->seller->id}}" @if($order->dispute->winner_id == $order->seller->id) selected @endif>{{ $order->seller->username }} (seller)</option>
						</select>
						<button type="submit">mark</button>
					</form>
					@endif
					<form action="{{ route('post.createdisputemessage', ['dispute' => $order->dispute->id]) }}" method="post" class="text-default">
						@csrf
						<div class="h3 mb-10">Create new message</div>
						<div class="form-group">
							<div class="label">
								<label for="message">message</label>
							</div>
							<textarea id="message" name="message" cols="50" rows="20"></textarea>
						</div>
						<button type="submit">submit</button>
					</form>
					<div class="messages">
			        	@foreach($messages as $message)
			        	<div class="container mt-10" style="width: 485px">
			            	{{ $message->decryptMessage() }}
			            	<div class="mt-20">
			                	<small>{{ $message->user->username }} - {{ $message->creationDate() }}</small>
			            	</div>
			        	</div>
			        	@endforeach
			    	</div>
			    	{{ $messages->links('includes.components.pagination') }}
				@endif
				@if($order->canceled())
					@if($order->isBuyer())
						<span>Order canceled! Your money has been returned.</span>
					@else
						<span>Order canceled!</span>
					@endif
				@endif
			</div>
		</div>
		<div class="inblock">
			<textarea style="background-color: #fff" cols="40" rows="20" disabled>{{ $order->address }}</textarea>
		</div>
	</div>
</div>

@stop
<?php

namespace App\Traits;

use App\Models\{Order,Feedback,Dispute};

trait OrderTrait
{
	use Payment, NotificationTrait;

	/**
	 * Mark the order as accepted
	 * @param  Order  $order
	 * 
	 * @return App\Models\Order
	 */
	private function markAsAccepted(Order $order)
	{
		if (!$order->paidOrder()) {
			throw new \Exception('The buyer has not yet sent the funds to the payment/escrow wallet!');
		}

		$order->status = 'accepted';
		$order->save();

		$buyerId = $order->buyer->id;
		$orderId = $order->id;
		$link = route('order', ['order' => $order->id]);

		$this->createNotification($buyerId, "Your order <strong>#$orderId</strong> has been accepted!", $link);
	}

	/**
	 * Mark the order as shipped
	 * @param  Order  $order 
	 * 
	 * @return App\Models\Order
	 */
	private function markAsShipped(Order $order)
	{
		$order->status = 'shipped';
		$order->save();

		$buyerId = $order->buyer->id;
		$orderId = $order->id;
		$link = route('order', ['order' => $order->id]);

		$this->createNotification($buyerId, "Your order <strong>#$orderId</strong> has been marked as shipped!", $link);
	}

	/**
	 * Mark the order as delivered
	 * @param  Order  $order 
	 * 
	 * @return App\Models\Order
	 */
	private function markAsDelivered(Order $order)
	{
		$order->status = 'delivered';
		$order->save();

		$this->releasePayment($order);

		$feedback = new Feedback();
		$feedback->order_id = $order->id;
		$feedback->product_id = $order->product->id;
		$feedback->buyer_id = $order->buyer->id;
		$feedback->seller_id = $order->seller->id;
		$feedback->save();

		$sellerId = $order->seller->id;
		$orderId = $order->id;
		$link = route('order', ['order' => $order->id]);

		$this->createNotification($sellerId, "Your sale <strong>#$orderId</strong> has been marked as delivered!", $link);
	}

	/**
	 * Mark the order as canceled
	 * @param  Order  $order 
	 * 
	 * @return App\Models\Order
	 */
	private function markAsCanceled(Order $order)
	{
		$order->status = 'canceled';
		$order->save();

		$this->cancelPayment($order);

		$orderId = $order->id;
		$link = route('order', ['order' => $order->id]);

		if (auth()->user()->id == $order->seller->id) {
			$userId = $order->buyer->id;
		} elseif (auth()->user()->id == $order->buyer->id) {
			$userId = $order->seller->id;
		}
		
		$this->createNotification($userId, "The order <strong>#$orderId</strong> has been cancelled!", $link);
	}

	/**
	 * Mark the order as disputed
	 * @param  Order  $order 
	 * 
	 * @return App\Models\Order
	 */
	private function markAsDisputed(Order $order)
	{
		$order->status = 'disputed';
		$order->save();

		if (!$order->delivered()) {
			$this->cancelPayment($order);
		}

		$dispute = new Dispute();
		$dispute->order_id = $order->id;
		$dispute->product_id = $order->product->id;
		$dispute->buyer_id = $order->buyer->id;
		$dispute->seller_id = $order->seller->id;
		$dispute->save();

		$orderId = $order->id;
		$link = route('order', ['order' => $order->id]);

		if (auth()->user()->id == $order->seller->id) {
			$userId = $order->buyer->id;
		} elseif (auth()->user()->id == $order->buyer->id) {
			$userId = $order->seller->id;
		}
		
		$this->createNotification($userId, "The order <strong>#$orderId</strong> has been disputed!", $link);
	}
}
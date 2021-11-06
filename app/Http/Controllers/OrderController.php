<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Tools\Converter;
use App\Traits\{OrderTrait,Payment};
use App\Models\{Order,Feedback,Dispute,DisputeMessage};

class OrderController extends Controller
{
	use Payment, OrderTrait;

	/**
	 * Checks if the authenticated user is allowed or not to view the purchase
	 * @param  Order  $order 
	 * 
	 * @return \Illuminate\Http\Response
	 */
	private function checkOrder(Order $order)
	{
		if (Gate::denies('order', $order)) {
			return abort(404);
		}
	}

	/**
	 * Check if user can access order feedback
	 * @param  Feedback $feedback 
	 * 
	 * @return \Illuminate\Http\Response
	 */
	private function checkFeedback(Feedback $feedback)
	{
		if (Gate::denies('feedback', $feedback)) {
			return abort(404);
		}
	}

	/**
	 * Order view
	 * @param  Order $order
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewOrder(Order $order)
	{
		$this->checkOrder($order);

		if ($order->disputed()) {
			$disputeMessages = $order->dispute->messages();
		}

		if ($order->delivered()) {
			$feedback = $order->feedback;
		}

		return view('master.order', [
			'order' => $order,
			'totalSent' => \Monerod::getTotalReceived($order->escrow_monero_wallet),
			'toPay' => number_format($order->total_in_monero, 5),
			'user' => $order->buyer,
			'feedback' => isset($feedback) ? $feedback : null,
			'messages' => isset($disputeMessages) ? $disputeMessages : null
		]);
	}

	/**
	 * Change the current status of the order to the next stage
	 * @param  Order $order
	 * @param  $status
	 * 
	 * @return App\Traits\Order
	 */
	public function postChangeOrderStatus(Order $order, $status)
	{
		$this->checkOrder($order);

		try {
			if ($status === $order->status) {
				throw new \Exception('The dispute is already in this status!');
			}

			if ($order->isSeller() && $order->waiting() && $status === 'accepted') {
				$this->markAsAccepted($order);
			} elseif ($order->isSeller() && $order->accepted() && $status === 'shipped') {
				$this->markAsShipped($order);
			} elseif ($order->isBuyer() && $order->shipped() && $status === 'delivered') {
				$this->markAsDelivered($order);
			} elseif (($order->waiting() || $order->accepted()) && $status === 'canceled') {
				$this->markAsCanceled($order);
			} elseif (!$order->waiting() && !$order->canceled() && $status === 'disputed') {
				$this->markAsDisputed($order);
			} else {
				throw new \Exception('Oops.. There was an error, try again!');
			}

			session()->flash('success', "Order status changed to successfully $status!");
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
		}

		return redirect()->route('order', ['order' => $order->id]);
	}

	/**
	 * Feedback HTTP request
	 * @param  Feedback $feedback
	 * @param  Request  $request
	 * 
	 * @return Illuminate\Routing\Redirector
	 */
	public function postFeedback(Feedback $feedback, Request $request)
	{
		$this->checkFeedback($feedback);

		$request->validate([
			'rating' => 'required|numeric|min:1|max:5',
			'type' => 'required',
			'feedback' => 'required|max:1000'			
		]);

		try {
			if (!in_array($request->type, config('general.feedback_type'))) {
				throw new \Exception('Invalid or unavailable feedback type!');
			}
			
			$feedback->rating = $request->rating;
			$feedback->type = $request->type;
			$feedback->message = $request->feedback;
			$feedback->save();
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
		}

		session()->flash('success', 'Your feedback has been sent successfully!');
		return redirect()->route('order', ['order' => $feedback->order->id]);
	}

	/**
	 * Create dispute message
	 * @param  Dispute $dispute 
	 * @param  Request $request 
	 * 
	 * @return Illuminate\Routing\Redirector
	 */
	public function postCreateDisputeMessage(Dispute $dispute, Request $request)
	{
		$this->checkOrder($dispute->order);

		try {
			$request->validate([
				'message' => 'required|max:1000'
			]);

			#Get auth user
			$user = auth()->user();

			$message = new DisputeMessage();
			$message->dispute_id = $dispute->id;
			$message->user_id = $user->id;
			$message->message = Crypt::encryptString($request->message);
			$message->save();
		} catch (\Exception $exception) {
			session()->flash('error', 'Oops... An error occurred!');
		}

		return redirect()->route('order', ['order' => $dispute->order_id]);
	}

	/**
	 * Finalize early HTTP request
	 * @param  Order  $order
	 * 
	 * @return App\Traits\Payment
	 */
	public function postFinalizearly(Order $order)
	{
		try {
			$this->checkFinalizearly($order);
			$this->finalizearly();
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
		}

		return redirect()->route('order', ['order' => $order->id]);
	}
}

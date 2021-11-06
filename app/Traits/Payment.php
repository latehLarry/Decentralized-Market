<?php

namespace App\Traits;

use Illuminate\Support\Facades\Gate;
use App\Tools\Converter;
use App\Models\Order;

trait Payment
{
	/**
	 * Remove the market commission amount from the total price
	 * @param $amount
	 *
	 * @return float
	 */
	public function comission($amount)
	{
		#Set variable
		$comissionAmount = 0;

		#Purchases greater than $100 give a 3% commission and purchases less than $100 give a 5% commission
		if ($amount > Converter::moneroConverter(100)) {
			$comissionAmount = $amount*config('general.market_fee.min');
		} else {
			$commissionAmount = $amount*config('general.market_fee.max');
		}

		return ($amount-$comissionAmount);
	}

	/**
	 * Check if the user can finish early
	 * @param  Order  $order
	 * 
	 * @return \Illuminate\Http\Response
	 */
	private function checkFinalizearly(Order $order)
	{
		if (Gate::denies('finalizearly', $order)) {
			return abort(404);
		}
	}

	/**
	 * Release the funds to the seller before receiving the product
	 * @param  Order  $order
	 * 
	 * @return App\Tools\Monerod
	 */
	private function finalizearly(Order $order)
	{
		try {
			$this->checkFinalizearly($order);

			$amount = $this->comission($order->total_in_monero);
			$walletReceiver = $order->seller->monero_wallet;

			\Monerod::transfer($amount, $walletReceiver);
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
		}
	}

	/**
	 * Release the funds to the seller
	 * @param  Order  $order
	 *
	 * @return App\Tools\Monerod
	 */
	private function releasePayment(Order $order)
	{
		try {
			$amount = $this->comission($order->total_in_monero);
			$walletReceiver = $order->seller->monero_wallet;

			\Monerod::transfer($amount, $walletReceiver);
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
		}
	}

	/**
	 * Cancels the payment (returns the retained money to the user)
	 * @param  Order  $order
	 * 
	 * @return App\Tools\Monerod
	  */
	private function cancelPayment(Order $order)
	{
		try {
			$amount = $order->total_in_monero;
			$walletReceiver = $order->buyer->monero_wallet;

			\Monerod::transfer($amount, $walletReceiver);
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
		}
	}
}

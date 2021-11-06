<?php

namespace App\Tools;

use App\Tools\MoneroLib\walletRPC;

class Monerod
{
	private $monerod;

	/**
	 * __construct
	 */
	public function __construct()
	{
        $this->monerod = new walletRPC([
            'host' => config('general.monero.host'),
            'port' => config('general.monero.port'),
            'user' => config('general.monero.username'),
            'password' => config('general.monero.password')
		]);
	}

	/**
	 * Validate monero address
	 *
	 * @return array
	 */
	public function validateAddress($address)
	{
		$result = $this->monerod->validate_address($address);

		if ($result['valid'] == false) {
			throw new \Exception('The address entered is invalid!');
		}
	}

	/**
	 * Create a monero account for the registered user
	 * @param  $tag
	 *
	 * @return array
	 */
	public function createNewAccount($tag = null)
	{
		try {
			$account = $this->monerod->create_account();

			#Defines the TAG of the created account
			$this->monerod->tag_accounts(array($account['account_index']), $tag);

			return $account['address'];
		} catch (\Exception $exception) {
			return null;
		}
	}

	/**
	 * Create new monero address
	 * @param  $paymentID
	 *
	 * @return array
	 */
	public function createNewAddress($paymentID = null)
	{
        try {
		    $result = $this->monerod->make_integrated_address($paymentID);

		    return $result['integrated_address'];
        } catch (\Exception $exception) {
            return null;   
        }
	}

	/**
	 * Get balance from a certain account
	 * @param  $tag
	 *
	 * @return float
	 */
	public function getBalance($tag = null)
	{
		try {
			$account = $this->monerod->get_accounts($tag);

			$balance = $account['total_unlocked_balance'];

			return number_format(($balance*0.000000000001), 5);
		} catch (\Exception $exception) {
			return number_format(0, 5);
		}
	}

	/**
	 * Get total received by address
	 * @param  $address
	 *
	 * @return float
	 */
	public function getTotalReceived($address)
	{
		#Get payment ID
		$result = $this->monerod->split_integrated_address($address);
		$paymentID = $result['payment_id'];

		#Set total received
		$totalReceived = 0.00000;

		#Get all payments
		$payments = $this->monerod->get_payments($paymentID);

		if (!empty($payments['payments'])) {
			foreach ($payments['payments'] as $payment) {
				$totalReceived += $payment['amount'];
			}
		}

		return number_format(($totalReceived*0.000000000001), 5);
	}

	/**
	 * Transfer moneros 
	 * @param  $amount
	 * @param  $address
	 * @param  $accountTag
	 *
	 * @return array
	 */
	public function transfer($amount, $address, $accountTag = null)
	{
		try {
			$result = $this->monerod->get_accounts($accountTag);

			#Get all accounts belonging to the user
			$accounts = $result['subaddress_accounts'];

			#Collects all indexes of user-owned accounts
			$accountIndexes = [];

			foreach ($accounts as $index => $account) {
				$accountIndexes[$index] = $account['account_index'];
			}

			#Transfer the requested amount using only the balance of the accounts belonging to the user
			$this->monerod->transfer(($amount*1.000000000000), $address, $accountIndexes);
		} catch (\Exception $exception) {
			session()->flash('error', 'Unable to transfer, please try again later!');
		}
	}
}

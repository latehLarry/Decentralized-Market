<?php

namespace App\Tools;

class Converter 
{
	/**
	 * Convert USD to authenticated user currency
	 * @param  float $amount
	 * @param  string $to, from 
	 *
	 * @return float
	 */
	public static function currencyConverter($amount, $to, $from = 'USD')
	{
		$content = file_get_contents("https://api.exchangerate.host/convert?from=$from&to=$to&amount=$amount");

		$respost = json_decode($content, true);
		$price = $respost['result'];	

		return number_format($price, 2);			
	}

	/**
	 * Returns the currency code
	 * @param  $currencySymbol
	 *
	 * @return string
	 */
	public static function getSymbol($currencySymbol)
	{
		return $currencySymbol;
	}

	/**
	 * Get the price of the monero in the last 60 minutes
	 * @param string $currency
	 *
	 * @return float
	 */
	public static function moneroLastPrice() 
	{
		#Get the last price in 60 minutes and set the cache
        $price = \Cache::remember('xmr_last_price', 3600, function() {
			$content = file_get_contents("https://min-api.cryptocompare.com/data/price?fsym=XMR&tsyms=USD");
			$respost = json_decode($content, true);

 			return $respost['USD'];
        });

		return $price;
	}

	/**
	 * Takes the Monero price in real time and divides it by the value (USD) that is being passed as a parameter
	 * @param  float $amount
	 *
	 * @return float
	 */
	public static function moneroConverter($amount)
	{
		$content = file_get_contents("https://min-api.cryptocompare.com/data/price?fsym=XMR&tsyms=USD");
		$respost = json_decode($content, true);
		$moneroPrice = $respost['USD'];

		return number_format($amount/$moneroPrice, 5);
	}
}

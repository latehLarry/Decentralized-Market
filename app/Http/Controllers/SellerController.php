<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\Converter;
use App\Http\Requests\Seller\{BecomeSellerRequest,SellerProfileRequest};

class SellerController extends Controller
{
	/**
	 * Become seller view
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewBecome()
	{
		#Get auth user
		$user = auth()->user();

		if (!$user->isSeller()) {
			return view('seller.becomeseller', [
				'user' => $user,
				'totalReceived' => \Monerod::getTotalReceived($user->become_monero_wallet),
				'sellerFee' => Converter::getSellerFee()
			]);
		}
		
		return abort(404);
	}

	/**
	 * Become seller HTTP request
	 * @param  BecomeSellerRequest $request
	 * 
	 * @return App\Http\Requests\Seller\BecomeSellerRequest
	 */
	public function postBecome(BecomeSellerRequest $request)
	{
		try {
			return $request->become();
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Return dashboard view
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewDashboard()
	{
		#Get auth user
		$user = auth()->user();

		return view('seller.dashboard', [
			'products' => $user->products()->paginate(10),
			'seller' => $user
		]);
	}

	/**
	 * Edit seller profile HTTP request
	 * @param  SellerProfileRequest $request
	 * 
	 * @return App\Http\Requests\Seller\SellerProfileRequest
	 */
	public function putDashboard(SellerProfileRequest $request)
	{
		try {
			return $request->edit();
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Sales view
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewSales()
	{
		#Get auth user
		$user = auth()->user();

		return view('seller.sales', [
			'user' => $user,
			'sales' => $user->sales()->paginate(15)
		]);
	}
}

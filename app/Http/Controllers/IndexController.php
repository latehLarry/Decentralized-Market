<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Cart\{AddToCartRequest,CheckoutRequest};
use App\Http\Requests\Product\ReportRequest;
use App\Http\Requests\Support\{CreateHelpRequest,CreateReplyHelpRequest};
use App\Models\{Category,Product,User,Report,Order,Notice,HelpRequest,Fan};

class IndexController extends Controller
{
	public function viewSetPgpKey()
	{
		if (session()->has('verification_name') and session()->get('verification_name') === 'confirm_new_pgp_key') {
			return view('setpgpkey', [
				''
			]);
		}
	}

	/**
	 * Result view
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewResult(Request $request)
	{
		#Filters
		$terms = $request->terms;
		$category = Category::where('slug', $request->category)->first();
		$order_by_param = $request->order_by;
		$ships_to = in_array($request->ships_to, array_keys(config('countries'))) ? $request->ships_to : null;
		$ships_from = in_array($request->ships_from, array_keys(config('countries'))) ? $request->ships_from : null;

		$order_by = 'ASC'; #default value

		if ($order_by_param == 'newest') {
			$order_by = 'DESC';
		} elseif ($order_by_param == 'oldest') {
			$order_by = 'ASC';
		}

		#Get products
		$products = Product::where(function($query) use($terms,$category,$ships_to,$ships_from) {
            if ($terms)
                $query->where('name', 'like', "%$terms%");
            if ($category)
                $query->where('category_id', $category->id);
            if ($ships_to)
                $query->where('ships_to', $ships_to);
            if ($ships_from)
                $query->where('ships_from', $ships_from);
            })->orderBy('created_at', $order_by);

		#Get all filters
		$filters = $request->all();

		return view('result', [
			'filters' => $filters,
			'terms' => $terms,
			'category' => $category,
			'orderBy' => $order_by_param,
			'ships_from' => $ships_from,
			'ships_to' => $ships_to,
			'products' => $products->paginate(10)
		]);
	}

	/**
	 * Home view
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewHome()
	{
		#Get auth user
		$user = auth()->user();

		$featuredProducts = Product::where('featured', true)->limit(12)->get();

		return view('home', [
			'featuredProducts' => $featuredProducts,
			'sellers' => $user->sellers
		]);
	}

	/**
	 * About view
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewAbout()
	{
		return view('about');
	}

	/**
	 * Notice view
	 * 
	 * @param  Notice $notice
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewNotice(Notice $notice)
	{
		return view('master.notice', [
			'notice' => $notice
		]);
	}

	/**
	 * View notice diary
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewNoticeDiary()
	{
		return view('master.notices', [
			'notices' => Notice::paginate(20)
		]);
	}

	/**
	 * Category view
	 * @param  $slug 
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewCategory($slug)
	{
		$category = Category::where('slug', $slug)->first();

		if (is_null($category)) {
			return abort(404);
		}

		return view('category', [
			'category' => $category,
			'products' => $category->products()->paginate(10)
		]);
	}

	/**
	 * Product view
	 * @param  Product $product
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewProduct(Product $product)
	{
		if ($product->deleted == false) {
			return view('master.product', [
				'product' => $product,
				'feedbacks' => $product->feedbacks()->paginate(10)
			]);
		}

		return abort(404);
	}   

	/**
	 * Seller profile view
	 * @param  $seller
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewSeller($seller)
	{
		$seller = User::where('username', $seller)->where('seller', true)->first();

		if (is_null($seller)) {
			return abort(404);
		}

		return view('seller.publicprofile', [
			'seller' => $seller,
			'products' => $seller->products()->paginate(10),
			'feedbacks' => $seller->feedbacks()->paginate(10)
		]);
	}

	/**
	 * Become a fan HTTP request
	 * 
	 * @param  $seller
	 * @return Illuminate\Routing\Redirector
	 */
	public function postFan($seller)
	{
		#Get auth user
		$user = auth()->user();

		#Get seller
		$seller = User::where('username', $seller)
						->where('seller', true)
						->first();

		if (is_null($seller)) {
			return abort(404);
		}

		try {
			#See if the authenticated user is the 
			if ($user->id == $seller->id) {
				throw new \Exception('You cannot become a fan of yourself!');
			}

			#Check if the user is already a fan of the seller
			if (!$user->isFan($seller)) {
				$fan = new Fan();
				$fan->seller_id = $seller->id;
				$fan->fan_id = $user->id;
				$fan->save();

				session()->flash('success', 'You have successfully become a fan of this seller!');
			} else {
				$fan = Fan::where('seller_id', $seller->id)
						  ->where('fan_id', $user->id)
						  ->first();

				session()->flash('success', 'You are no longer a fan of this seller successfully!');

				#Delete
				$fan->delete();
			}
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
		}

		return redirect()->route('seller', ['seller' => $seller->username]);
	}

	/**
	 * Cart view
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewCart()
	{
		#Get all products from the cart
		$allProducts = session()->has('cart') ? session()->get('cart') : [];

		#Count total products
		$totalProducts = count($allProducts);

		#Set total price
		$totalPrice = 0;

		#Sum the total of all products
		foreach ($allProducts as $product) {
			$totalPrice += $product['total'];
		}

		return view('master.cart', [
			'products' => $allProducts,
			'totalProducts' => $totalProducts,
			'totalPrice' => $totalPrice
		]);
	}

	/**
	 * Add product to cart HTTP request
	 * @param AddToCartRequest $request
	 * @param Product 		   $product
	 * 
	 * @return App\Http\Requests\Cart\AddToCartRequest
	 */
	public function postAddToCart(AddToCartRequest $request, Product $product)
	{
		try {
			return $request->add($product);
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Remove product to cart HTTP request
	 * @param  $product
	 * 
	 * @return Illuminate\Routing\Redirector
	 */
	public function postRemoveToCart($product) 
	{
		#Get all products from the cart
		$products = session()->has('cart') ? session()->get('cart') : [];

		#Find the product in the cart and delete it
		foreach ($products as $index => $productCart) {
			if ($productCart['product_id'] == $product) {
				unset($products[$index]);
			}
		}

		session()->put('cart', $products);
		return redirect()->route('cart');
	}

	/**
	 * Clear cart HTTP request
	 * 
	 * @return Illuminate\Routing\Redirector
	 */
	public function postClearCart()
	{
		#Clear the cart session if it exists
		if (session()->has('cart')) {
			session()->forget('cart');
		}

		return redirect()->route('cart');
	}

	/**
	 * Checkout HTTP request
	 * @param  CheckoutRequest $request
	 * 
	 * @return App\Http\Requests\Cart\CheckoutRequest
	 */
	public function postCheckout(CheckoutRequest $request)
	{
		try {
			return $request->checkout();
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Report view
	 * @param  Product $product
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewReport(Product $product)
	{
		return view('master.reportproduct', [
			'product' => $product
		]);
	}

	/**
	 * Product report HTTP request
	 * @param  ReportRequest       $request
	 * @param  Product       	   $product
	 * 
	 * @return App\Http\Requests\Product\ReportRequest
	 */
	public function postReport(ReportRequest $request, Product $product)
	{
		try {
			return $request->report($product);
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Check if the user has permission to view the help request
	 * @param  HelpRequest $helpRequest
	 * 
	 * @return Illuminate\Http\Response
	 */
	private function checkHelpRequest(HelpRequest $helpRequest)
	{
		if (Gate::denies('help-request', $helpRequest)) {
			return abort(404);
		}
	}

	/**
	 * Support view
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewSupport()
	{
		#Get auth user
		$user = auth()->user();

		return view('master.support', [
			'helpRequests' => $user->helprequests()->paginate(10)
		]);
	}

	/**
	 * Create help HTTP request
	 * @param  CreateHelpRequest $request
	 * 
	 * @return App\Http\Requests\Support\CreateHelpRequest
	 */
	public function postCreateHelpRequest(CreateHelpRequest $request)
	{
		try {
			return $request->new();
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Help request view
	 * @param  HelpRequest $helpRequest
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewHelpRequest(HelpRequest $helpRequest)
	{
		$this->checkHelpRequest($helpRequest);

		return view('master.helprequest', [
			'helpRequest' => $helpRequest,
			'messages' => $helpRequest->messages()->paginate(15)
		]);
	}

	/**
	 * Create help reply HTTP request
	 * @param  HelpRequest            $helpRequest 
	 * @param  CreateReplyHelpRequest $request
	 * 
	 * @return App\Http\Requests\Support\CreateReplyHelpRequest
	 */
	public function postHelpRequest(HelpRequest $helpRequest, CreateReplyHelpRequest $request)
	{
		$this->checkHelpRequest($helpRequest);
		
		try {
			return $request->new($helpRequest);
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
			return redirect()->back();
		}
	}
}
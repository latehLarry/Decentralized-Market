<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Product\{ImageRequest,OfferRequest,DeliveryRequest,InformationsRequest};
use App\Models\{Product,Image,Offer,Delivery,Category,Favorite};

class ProductController extends Controller
{
	/**
	 * Checks whether or not the user has products permission
	 * 
	 * @return \Illuminate\Http\Response
	 */
	private function product()
	{
		if (Gate::denies('product')) {
			return abort(404);
		}
	}

	/**
	 * Check whether the authenticated user can change the product or not
	 * @param  Product $product
	 * 
	 * @return \Illuminate\Http\Response
	 */
	private function updateProduct(Product $product)
	{
		if (Gate::denies('update-product', $product)) {
			return abort(404);
		}
	}

	/**
	 * Images product view
	 * @param  string       $section 
	 * @param  Product      $product 
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewImages($section, Product $product = null)
	{
		$this->product();

		if ($section === 'add' && is_null($product)) {
			return view('includes.forms.product.images', [
				'images' => session()->has('images') ? session()->get('images') : [], 
				'section' => $section
			]);
		} elseif ($section === 'edit' && !is_null($product) && $product->exists()) {

			$this->updateProduct($product);

			return view('includes.forms.product.images', [
				'product' => $product,
				'section' => $section
			]);
		} else {
			return abort(404);
		}
	}

	/**
	 * Images product HTTP request
	 * @param  string       $section 
	 * @param  Product      $product 
	 * @param  ImageRequest $request 
	 * 
	 * @return App\Http\Requests\Product\ImageRequest         
	 */
	public function postImage($section, Product $product = null, ImageRequest $request)
	{
		$this->product();

		if ($section === 'add' && is_null($product)) {
			try {
				return $request->add();
			} catch (\Exception $exception) {
				session()->flash('error', $exception->getMessage());
				return redirect()->back();
			}
		} elseif ($section === 'edit' && !is_null($product) && $product->exists()) {

			$this->updateProduct($product);
			
			try {
				return $request->edit($product);
			} catch (\Exception $exception) {
				session()->flash('error', $exception->getMessage());
				return redirect()->back();
			}
		} else {
			return abort(404);
		}
	}

	/**
	 * Delete image HTTP request
	 * @param  string       $section 
	 * @param  Product      $product 
	 * @param  string       $image
	 *    
	 * @return Illuminate\Routing\Redirector
	 */
	public function postDeleteImage($section, $image, Product $product = null)
	{
		$this->product();

		if ($section === 'add' && is_null($product)) {
			#Get all items from session images if they exist
			$images = session()->has('images') ? session()->get('images') : [];
			#Takes the array where the image corresponds to the UUID and takes it out of the session
			foreach ($images as $index => $img) {
				if ($img['uuid'] == $image) {
					unset($images[$index]);
				}
			}

			#Save the session
			session()->put('images', $images);
			return redirect()->back();
		} elseif ($section === 'edit' && !is_null($product) && $product->exists()) {

			$this->updateProduct($product);

        	try {
	        	#Do not allow the user to delete the last image
        		if (count($product->images) == 1) {
            		throw new \Exception('The product must have at least one image!');
        		}

				#Find the image corresponds to the parameter $image
				$image = Image::findOrFail($image);

				#Check if the image belongs to the product
				if ($image->product->id == $product->id) {
					$image->delete();
				} 
			} catch (\Exception $exception) {
				session()->flash('error', $exception->getmessage());
			}
	
			return redirect()->route('images', ['section' => $section, 'product' => $product->id]);
		} else {
			return abort(404);
		}
	}


	/**
	 * Offers product view
	 * @param  string       $section 
	 * @param  Product      $product 
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewOffers($section, Product $product = null)
	{
		$this->product();

		if ($section === 'add' && is_null($product)) {
			return view('includes.forms.product.offers', [
				'offers' => session()->has('offers') ? session()->get('offers') : [],
				'section' => $section
			]);
		} elseif ($section === 'edit' && !is_null($product) && $product->exists()) {

			$this->updateProduct($product);

			return view('includes.forms.product.offers', [
				'product' => $product,
				'section' => $section	
			]);
		} else {
			return abort(404);
		}
	}

	/**
	 * Offers HTTP request
	 * @param  string       $section 
	 * @param  Product      $product 
	 * @param  OfferRequest $request 
	 * 
	 * @return App\Http\Requests\Product\Offers             
	 */
	public function postOffer($section, Product $product = null, OfferRequest $request)
	{
		$this->product();

		if ($section === 'add' && is_null($product)) {
			try {
				return $request->add();
			} catch (\Exception $exception) {
				session()->flash('error', $exception->getMessage());
				return redirect()->back();
			}
		} elseif ($section === 'edit' && !is_null($product) && $product->exists()) {

			$this->updateProduct($product);

			try {
				return $request->edit($product);
			} catch (\Exception $exception) {
				session()->flash('error', $exception->getMessage());
				return redirect()->back();
			}
		} else {
			return abort(404);
		}
	}

	/**
	 * Delete offer HTTP request
	 * @param  string       $section 
	 * @param  Product      $product 
	 * @param  string       $offer   
	 * 
	 * @return Illuminate\Routing\Redirector
	 */
	public function postDeleteOffer($section, $offer, Product $product = null)
	{
		$this->product();

		if ($section === 'add' && is_null($product)) {
			#Get all items from session offers if they exist
			$offers = session()->has('offers') ? session()->get('offers') : [];
			#Takes the array where the image corresponds to the UUID and takes it out of the session
			foreach ($offers as $index => $ofr) {
				if ($ofr['uuid'] == $offer) {
					unset($offers[$index]);
				}
			}

			#Save the session
			session()->put('offers', $offers);
			return redirect()->back();
		} elseif ($section === 'edit' && !is_null($product) && $product->exists()) {

			$this->updateProduct($product);

        	try {
        		#Do not allow the user to delete the last offer
        		if (count($product->offers) == 1) {
            		throw new \Exception('The product must have at least one offer!');
        		}

				#Find the offer corresponds to the parameter $offer
				$offer = Offer::findOrFail($offer);

				#Check if the offer belongs to the product
				if ($offer->product->id == $product->id) {
					$offer->deleted = true;
					$offer->save();
				}
			} catch(\Exception $exception) {
				session()->flash('error', $exception->getMessage());
			}
		
			return redirect()->route('offers', ['section' => $section, 'product' => $product->id]);
		} else {
			return abort(404);
		}
	}

	/**
	 * Deliveries product view
	 * @param  string       $section 
	 * @param  Product      $product 
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewDeliveries($section, Product $product = null)
	{
		$this->product();

		if ($section === 'add' && is_null($product)) {
			return view('includes.forms.product.deliveries', [
				'deliveries' => session()->has('deliveries') ? session()->get('deliveries') : [],
				'section' => $section
			]);
		} elseif ($section === 'edit' && !is_null($product) && $product->exists()) {

			$this->updateProduct($product);

			return view('includes.forms.product.deliveries', [
				'product' => $product,
				'section' => $section
			]);
		} else {
			return abort(404);
		}
	}

	/**
	 * Deliveries HTTP request
	 * @param  string          $section 
	 * @param  Product         $product 
	 * @param  DeliveryRequest $request 
	 * 
	 * @return App\Http\Requests\Product\DeliveryRequest             
	 */
	public function postDelivery($section, Product $product = null, DeliveryRequest $request)
	{
		$this->product();

		if ($section === 'add' && is_null($product)) {

			try {
				return $request->add();
			} catch (\Exception $exception) {
				session()->flash('error', $exception->getMessage());
				return redirect()->back();
			}

		} elseif ($section === 'edit' && !is_null($product) && $product->exists()) {

			$this->updateProduct($product);

			try {
				return $request->edit($product);
			} catch (\Exception $exception) {
				session()->flash('error', $exception->getMessage());
				return redirect()->back();
			}

		} else {
			return abort(404);
		}
	}

	/**
	 * Delete delivery HTTP request
	 * @param  string       $section 
	 * @param  Product      $product 
	 * @param  string       $delivery 
	 *   
	 * @return Illuminate\Routing\Redirector
	 */
	public function postDeleteDelivery($section, $delivery, Product $product = null)
	{
		$this->product();

		if ($section === 'add' && is_null($product)) {
			#Get all items from session deliveries if they exist
			$deliveriesMethods = session()->has('deliveries') ? session()->get('deliveries') : [];

			#Takes the array where the image corresponds to the UUID and takes it out of the session
			foreach ($deliveriesMethods as $index => $deliveryMthd) {
				if ($deliveryMthd['uuid'] == $delivery) {
					unset($deliveriesMethods[$index]);
				}
			}

			#Save the session
			session()->put('deliveries', $deliveriesMethods);
			return redirect()->back();
		} elseif ($section === 'edit' && !is_null($product) && $product->exists()) {
        	
			$this->updateProduct($product);

        	try {
               #Do not allow the user to delete the last delivery method
        		if (count($product->deliveries) == 1) {
            		throw new \Exception('The product must have at least one delivery method!');
        		}

				#Find the delivery method corresponds to the parameter $delivery
				$delivery = Delivery::findOrFail($delivery);

				#Check if the delivery belongs to the product
				if ($delivery->product->id == $product->id) {
					$delivery->deleted = true;
					$delivery->save();
				} 
			} catch(\Exception $exception) {
				session()->flash('error', $exception->getMessage());
			}
		
			return redirect()->route('deliveries', ['section' => $section, 'product' => $product->id]);
		} else {
			return abort(404);
		}
	}

	/**
	 * Informations product view
	 * @param  string       $section 
	 * @param  Product      $product 
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewInformations($section, Product $product = null)
	{
		$this->product();

		if ($section === 'add' && is_null($product)) {
			return view('includes.forms.product.informations', [
				'section' => $section,
				'categories' => Category::get() 
			]);
		} elseif ($section === 'edit' && !is_null($product) && $product->exists()) {
			$this->updateProduct($product);
			return view('includes.forms.product.informations', [
				'product' => $product,
				'section' => $section,
				'categories' => Category::get() 
			]);
		} else {
			return abort(404);
		}
	}

	/**
	 * Informations HTTP request
	 * @param  string              $section 
	 * @param  Product             $product 
	 * @param  InformationsRequest $request 
	 * 
	 * @return App\Http\Requests\Product\InformationsRequest            
	 */
	public function postInformations($section, Product $product = null, InformationsRequest $request)
	{
		$this->product();

		if ($section === 'add' && is_null($product)) {
			try {
				return $request->add();
			} catch (\Exception $exception) {
				session()->flash('error', $exception->getMessage());
				return redirect()->back();
			}
		} elseif ($section === 'edit' && !is_null($product) && $product->exists()) {

			$this->updateProduct($product);

			try {
				return $request->edit($product);
			} catch (\Exception $exception) {
				session()->flash('error', $exception->getMessage());
				return redirect()->back();
			}
		} else {
			return abort(404);
		}
	}

	/**
	 * Delete product HTTP request
	 * @param  Product $product 
	 * 
	 * @return Illuminate\Routing\Redirector
	 */
	public function postDeleteProduct(Product $product)
	{
		$this->updateProduct($product);
		
		#Delete product
		$product->deleted = true;
		$product->featured = false;
		$product->save();

		#Removes product from users' favorites
		$allFavorites = Favorite::where('product_id', $product->id)->get();

		foreach ($allFavorites as $favorite) {
			$favorite->delete();
		}

		session()->flash('success', 'Successfully deleted product!');
		return redirect()->back();
	}
}
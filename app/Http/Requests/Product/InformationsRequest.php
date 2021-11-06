<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\{Product,Image,Offer,Delivery};

class InformationsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:50',
            'category' => 'required|exists:categories,id',
            'description' => 'required|max:5000',
            'refund_policy' => 'required|max:5000',
            'ships_from' => 'required',
            'ships_to' => 'required'
        ];
    }

    /**
     * Check if the country the user has chosen is valid
     */
    private function checkCountries()
    {
        if (!in_array($this->ships_from, array_keys(config('countries'))) or !in_array($this->ships_to, array_keys(config('countries')))) {
            throw new \Exception('The chosen country is invalid!');
        }
    }

    /**
     * Add product
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function add()
    {
        $this->checkCountries();

        #Check if there are images in the session and how many there are
        $totalImages = session()->has('images') ? count(session()->get('images')) : 0;

        if ($totalImages == 0) {
            throw new \Exception('You need to add at least one image to the product!');
        }

        #Check if there are offers in the session and how many there are
        $totalOffers = session()->has('offers') ? count(session()->get('offers')) : 0;

        if ($totalOffers == 0) {
            throw new \Exception('You need to add at least one offer to the product!');
        }

        #Check if there are delivery methods in the session and how many there are
        $totalDeliveryMethods = session()->has('deliveries') ? count(session()->get('deliveries')) : 0;

        if ($totalDeliveryMethods == 0) {
            throw new \Exception('You need to add at least one delivery method for the product!');           
        }

        #Create a new product
        $product = new Product();
        $product->seller_id = auth()->user()->id;
        $product->category_id = $this->category;
        $product->name = $this->name;
        $product->description = $this->description;
        $product->refund_policy = $this->refund_policy;
        $product->ships_from = $this->ships_from;
        $product->ships_to = $this->ships_to;
        $product->save();

        #Save session product images to database
        $images = session()->get('images');

        foreach ($images as $image) {
            $img = new Image();
            $img->id = $image['uuid'];
            $img->product_id = $product->id;
            $img->image = $image['image'];
            $img->save();
        }

        #Save session product offers to database
        $offers = session()->get('offers');

        foreach ($offers as $offer) {
            $ofr = new Offer();
            $ofr->id = $offer['uuid'];
            $ofr->product_id = $product->id;
            $ofr->quantity = $offer['quantity'];
            $ofr->price = $offer['price'];
            $ofr->mesure = $offer['mesure'];
            $ofr->save();
        }

        #Save session product delivery methods to database
        $deliveryMethods = session()->get('deliveries');

        foreach ($deliveryMethods as $deliveryMethod) {
            $deliveryMthd = new Delivery();
            $deliveryMthd->id = $deliveryMethod['uuid'];
            $deliveryMthd->product_id = $product->id;
            $deliveryMthd->name = $deliveryMethod['name'];
            $deliveryMthd->price = $deliveryMethod['price'];
            $deliveryMthd->save();
        }

        #Clear session
        session()->forget(['images', 'offers', 'deliveries']);

        session()->flash('success', 'Product created successfully!');
        return redirect()->route('seller.dashboard');
    }

    /**
     * Edit product
     * @param  Product $product
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function edit(Product $product)
    {
        $this->checkCountries();

        $product->category_id = $this->category;
        $product->name = $this->name;
        $product->description = $this->description;
        $product->refund_policy = $this->refund_policy;
        $product->ships_from = $this->ships_from;
        $product->ships_to = $this->ships_to;
        $product->save();

        session()->flash('success', 'Product edited successfully!');
        return redirect()->back();
    }
}

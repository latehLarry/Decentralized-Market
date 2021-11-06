<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use App\Models\{Product,Offer};

class OfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'quantity' => 'required|numeric|min:0.01|max:999999',
            'price' => 'required|numeric|min:0.01|max:999999',
            'mesure' => 'required|max:10'
        ];
    }

    /**
     * Add product offer
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function add()
    {
        session()->push('offers', [
            'uuid' => Str::uuid(),
            'quantity' => $this->quantity,
            'price' => $this->price,
            'mesure' => $this->mesure
        ]);

        session()->flash('success', 'Offer created successfully!');
        return redirect()->back();
    }

    /**
     * Edit product offer HTTP respost
     * @param  Product $product
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function edit(Product $product)
    {
        $offer = new Offer();
        $offer->id = Str::uuid();
        $offer->product_id = $product->id;
        $offer->quantity = $this->quantity;
        $offer->price = $this->price;
        $offer->mesure = $this->mesure;
        $offer->save();

        session()->flash('success', 'Offer created successfully!');
        return redirect()->route('offers', ['section' => 'edit', 'product' => $product->id]);     
    }
}

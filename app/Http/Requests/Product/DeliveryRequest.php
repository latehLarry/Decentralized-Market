<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use App\Models\{Product,Delivery};

class DeliveryRequest extends FormRequest
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
            'name' => 'required|max:10',
            'days' => 'required|numeric|max:30',
            'price' => 'required|numeric|min:0|max:999999'
        ];
    }

    /**
     * Add product delivery
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function add()
    {
        session()->push('deliveries', [
            'uuid' => Str::uuid(),
            'name' => $this->name,
            'days' => $this->days,
            'price' => $this->price
        ]);

        session()->flash('success', 'Delivery method created successfully!');
        return redirect()->back();
    }

    /**
     * Edit product delivery
     * @param  Product $product
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function edit(Product $product)
    {
        $delivery = new Delivery();
        $delivery->id = Str::uuid();
        $delivery->product_id = $product->id;
        $delivery->name = $this->name;
        $delivery->days = $this->days;
        $delivery->price = $this->price;
        $delivery->save();

        session()->flash('success', 'Delivery method created successfully!');
        return redirect()->route('deliveries', ['section' => 'edit', 'product' => $product->id]);     
    }
}

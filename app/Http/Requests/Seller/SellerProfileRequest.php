<?php

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class SellerProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->isSeller();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => 'nullable|max:5000',
            'rules' => 'nullable|max:5000'
        ];
    }

    /**
     * Edit seller profile HTTP respost
     * @return function
     */
    public function edit()
    {
        #Get auth user (seller)
        $seller = auth()->user();

        $seller->seller_description = $this->description;
        $seller->seller_rules = $this->rules;
        $seller->save();

        session()->flash('success', 'Profile successfully changed!');
        return redirect()->back();
    }
}

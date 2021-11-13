<?php

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Tools\Converter;

class BecomeSellerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && !auth()->user()->isSeller();
    }

    /**
     * Get the validation rules that apply to the request
     *
     * @return array
     */
    public function rules()
    {
        return [
            'terms' => 'accepted'
        ];
    }

    /**
     * Database persist
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function become()
    {
        #Updates authenticated user role for seller
        $user = auth()->user();

        if (is_null($user->pgp_key)) {
            throw new \Exception('You must have a pgp key linked to your account!');
        }

        if (!$user->paidSellerFee()) {
            throw new \Exception('Deposit the required amount at the address!');
        }

        $user->seller = true;
        $user->seller_since = Carbon::now();
        $user->save();

        return redirect()->route('seller.dashboard');
    }
}

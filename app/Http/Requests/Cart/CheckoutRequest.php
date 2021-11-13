<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use App\Tools\{PGP,Converter};
use App\Traits\NotificationTrait;
use App\Models\{Order,User};

class CheckoutRequest extends FormRequest
{
    use NotificationTrait;

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
            'address' => 'required',
            'pin' => 'required'
        ];
    }

    /**
     * Checkout
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function checkout()
    {
        #Get auth user
        $buyer = auth()->user();

        if (!Hash::check($this->pin, $buyer->pin)) {
            throw new \Exception('The pin entered is incorrect!');
        }

        #Get products to cart
        $products = count(session()->get('cart')) > 0 ? session()->get('cart') : [];

        try {
            foreach ($products as $product) {
                $seller = User::findOrFail($product['seller_id']);

                $order = new Order();
                $order->product_id = $product['product_id'];
                $order->buyer_id = $buyer->id;
                $order->seller_id = $seller->id;
                $order->address = PGP::encryptMessage($seller->pgp_key, $this->address);
                $order->delivery_method = $product['delivery_method'];
                $order->quantity = $product['quantity'];
                $order->escrow_monero_wallet = \Monerod::createNewAddress();
                $order->total = $product['total'];
                $order->total_in_monero = Converter::moneroConverter($product['total']);
                $order->save();

                #Create notification
                $this->createNotification(
                                            $order->seller->id,
                                            'A user has opened a new order #<strong>'.$order->id.'</strong>', 
                                            Route('order', ['order' => $order->id])
                                        );
            }

            #Clear cart
            session()->forget('cart');

            return redirect()->route('orders', ['status' => 'waiting']);
        } catch (\Exception $exception) {
            throw new \Exception('Oops... An error has occurred!');
        }
    }
}

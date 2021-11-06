<?php

namespace App\Http\Requests\User\Balance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use App\Tools\Converter;
use App\Models\{User,Transition};

class TransferRequest extends FormRequest
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
            'username' => 'required|exists:users,username',
            'value' => 'required|numeric|min:1',
            'pin' => 'required'
        ];
    }

    /**
     * Transfer bitcoin
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function transfer()
    {
        #Get auth user
        $user = auth()->user();

        if (!Hash::check($this->pin, $user->pin)) {
            throw new \Exception('The PIN entered is incorrect!');
        }

        #Convert the value entered in USD to XMR
        $amount = Converter::moneroConverter($this->value);

        #Compare
        if ($amount > $user->balance()) {
            throw new \Exception('Your amount to be transferred is greater than the balance you have in your account!');
        }

        #Get the user who will receive the bitcoins
        $receiver = User::where('username', $this->username)->first();

        if ($user == $receiver) {
            throw new \Exception('You cannot send moneros to yourself!');
        }

        $walletReceiver = $receiver->monero_wallet;
        \Monerod::transfer($amount, $walletReceiver, $user->id);

        #Create a transition record (ISSUER)
        $transitionIssuer = new Transition();
        $transitionIssuer->user_id = $user->id;
        $transitionIssuer->action = 'Monero transfer(sent)';
        $transitionIssuer->amount = "-$amount"; 
        $transitionIssuer->balance = $user->balance();
        $transitionIssuer->save();

        #Create a transition record (RECEIVER)
        $transitionReceiver = new Transition();
        $transitionReceiver->user_id = $receiver->id;
        $transitionReceiver->action = 'Monero transfer(received)';
        $transitionReceiver->amount = "+$amount";
        $transitionReceiver->balance = $receiver->balance();
        $transitionReceiver->save();

        session()->flash('success', 'Moneros sent successfully!');
        return redirect()->route('balance');
    }
}

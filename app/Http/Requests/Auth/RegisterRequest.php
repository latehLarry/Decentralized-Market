<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\{Hash,Auth};
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     *
     * @return bool
     */
    public function authorize()
    {
        return !auth()->check();
    }

    /**
     * Get the validation rules that apply to the request
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|unique:users,username|alpha_num|min:4|max:20',
            'password' => 'required|min:8|max:80|confirmed',
            'pin' => 'required|digits:6|confirmed',
            'reference_code' => 'nullable|exists:users,reference_code',
            'captcha' => 'required|captcha'
        ];
    }

    /**
     * Get custom messages from requisition rules
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'captcha.captcha' => 'The captcha is incorrect' 
        ];
    }

    /**
     * Database persist
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function register()
    {
        $user = new User();
        $user->username = $this->username;
        $user->password = Hash::make($this->password);
        $user->pin = Hash::make($this->pin);
        $user->reference_code = Str::random(15);
        $user->last_login = Carbon::now();

        if (!is_null($this->reference_code)) {
            #Get affiliate who referred the new user
            $affiliate = User::where('reference_code', $this->reference_code)->first();

            $user->referenced_by = $affiliate->id;
        }

        $user->save();

        #Creates a wallet for the new user. If the daemon is not active, the return value is null
        $user->monero_wallet = \Monerod::createNewAccount($user->id);
        $user->become_monero_wallet = \Monerod::createNewAddress();
        $user->save();

        #Start authentication
        Auth::login($user);
        session()->regenerate();

        return redirect()->route('accountindex');
    }
}

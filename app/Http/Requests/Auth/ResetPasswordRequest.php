<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\{Hash,Crypt};
use App\Tools\PGP;
use App\Models\User;

class ResetPasswordRequest extends FormRequest
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
            'username' => 'nullable',
            'verification_code' => 'nullable',
            'new_password' => 'nullable|min:8|max:80|confirmed',
            //'captcha' => 'required|captcha'
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
    public function createRequest()
    {
        if (is_null($this->username)) {
            throw new \Exception('Enter your username!');
        }

        #Get user
        $user = User::where('username', $this->username)->first();

        if (is_null($user) or is_null($user->pgp_key)) {
            throw new \Exception('User does not exist or does not have a PGP key!');
        }

        #Get user username
        session()->put('user_username', $user->username);

        #Create verification
        PGP::verification($user->pgp_key, 'reset_password');

        return redirect()->back();
    }

    /**
     * Reset password
     *
     * @return Illuminate\Routing\Redirector
     */
    public function reset()
    {
        if (!session()->has('verification_code') or session()->get('verification_name') !== 'reset_password') {
            return redirect()->back();
        }

        #Decrypt verification code
        $verificationCode = Crypt::decryptString(session()->get('verification_code'));

        $username = session()->get('user_username');

        if (is_null($this->new_password)) {
            throw new \Exception('Please enter a new password and confirm!');
        }

        if ($verificationCode !== $this->verification_code) {
            throw new \Exception('Invalid verification code!');
        }

        $user = User::where('username', $username)->first();
        $user->password = Hash::make($this->new_password);
        $user->save();

        #Clear sessions
        session()->forget(['verification_name', 'encrypted_message','verification_code', 'user_username']);

        session()->flash('success', 'Password reset successfully!');
        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;

class VerifyLoginRequest extends FormRequest
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
            'verification_code' => 'nullable'
        ];
    }

    /**
     * Verify login HTTP request
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function verify()
    {
        #Decrypt session verification code
        $verificationCode = Crypt::decryptString(session()->get('verification_code'));

        if ($this->verification_code !== $verificationCode) {
            throw new \Exception('Invalid verification code!');
        }

        #Destroy verification sessions
        session()->forget(['verification_name', 'encrypted_message', 'verification_code']);

        return redirect()->route('accountindex');
    }
}

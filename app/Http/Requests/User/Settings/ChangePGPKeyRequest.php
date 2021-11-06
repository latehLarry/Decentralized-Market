<?php

namespace App\Http\Requests\User\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use App\Tools\PGP;

class ChangePGPKeyRequest extends FormRequest
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
            'pgp_key' => 'nullable',
            'verification_code' => 'nullable'
        ];
    }

    /**
     * Create change order
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function createRequisition() 
    {
        $pgpKey = $this->pgp_key;

        if (is_null($pgpKey)) {
            throw new \Exception('Please enter a valid PGP key for verification!');
        }

        session()->put('pgp_key', $pgpKey); #Create a new session to store the PGP key entered in the field
        PGP::verification($pgpKey, 'confirm_new_pgp_key'); #Create new verification

        return redirect()->route('settings', '#pgpkey');
    }

    /**
     * Database persist
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function change()
    {
        #Get auth user
        $user = auth()->user();

        if (!session()->has('verification_name')) {
            throw new \Exception('Oops... Cancel and try again!');
        }

        #Decrypt session verification code
        $verificationCode = Crypt::decryptString(session()->get('verification_code'));

        if ($this->verification_code !== $verificationCode) {
            throw new \Exception('Invalid verification code!');
        }

        #Set new PGP key
        $user->pgp_key = session()->get('pgp_key');
        $user->save();

        #Destroy verification sessions
        session()->forget(['pgp_key', 'verification_name', 'encrypted_message', 'verification_code']);

        session()->flash('success', 'PGP key changed successfully!');
        return redirect()->route('settings');
    }
}

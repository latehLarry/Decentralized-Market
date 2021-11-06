<?php

namespace App\Http\Requests\User\Settings;

use Illuminate\Foundation\Http\FormRequest;

class ChangeBackupWalletRequest extends FormRequest
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
            'monero_wallet_address' => 'nullable'
        ];
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

        \Monerod::validateAddress($this->monero_wallet_address);

        $user->backup_monero_wallet = $this->monero_wallet_address;
        $user->save();

        session()->flash('success', 'Backup wallet successfully changed!');
        return redirect()->route('settings');
    }
}

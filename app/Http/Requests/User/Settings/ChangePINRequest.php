<?php

namespace App\Http\Requests\User\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ChangePINRequest extends FormRequest
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
            'current_pin' => 'required',
            'new_pin' => 'required|digits:6|confirmed',
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

        if (!Hash::check($this->current_pin, $user->pin)) {
            throw new \Exception('Incorrect current PIN!');
        }

        $user->pin = Hash::make($this->new_pin);
        $user->save();

        session()->flash('success', 'PIN changed successfully!');
        return redirect()->route('settings');
    }
}

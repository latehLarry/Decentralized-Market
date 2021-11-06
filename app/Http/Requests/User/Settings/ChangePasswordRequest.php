<?php

namespace App\Http\Requests\User\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
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
            'current_password' => 'required',
            'new_password' => 'required|min:8|max:80|confirmed',
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

        if (!Hash::check($this->current_password, $user->password)) {
            throw new \Exception('Incorrect current password!');
        }

        $user->password = Hash::make($this->new_password);
        $user->save();

        session()->flash('success', 'Password changed successfully!');
        return redirect()->route('settings');
    }
}

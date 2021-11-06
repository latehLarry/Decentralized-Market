<?php

namespace App\Http\Requests\User\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class ChangeAvatarRequest extends FormRequest
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
            'avatar' => 'required|image|mimes:jpeg,jpg,png|dimensions:min_width=96,min_height=96|max:30',
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

        $avatar = $this->avatar->store('img'); #Save avatar image
        
        $path = $_SERVER['DOCUMENT_ROOT']."/storage/$avatar"; #Take the avatar's path
        $type = pathinfo($path, PATHINFO_EXTENSION); #Get avatar image type
        $image = file_get_contents($path); #Get the avatar image
        $avatarBase64 = "data:image/$type;base64,".base64_encode($image); #Convert avatar image to base64
        Storage::delete($avatar); #Delete the avatar image from the server as it is no longer needed

        $user->avatar = $avatarBase64;
        $user->save();

        session()->flash('success', 'Avatar successfully changed!');
        return redirect()->route('settings');
    }
}

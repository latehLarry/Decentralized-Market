<?php

namespace App\Http\Requests\Staff\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use App\Models\{User,Product,Favorite};

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|alpha_num|min:4|max:20',
            'reference_code' => 'required|min:5',
            'finalizearly' => 'required|boolean',
            'role' => 'nullable'
        ];
    }

    /**
     * Database persist
     * @param  User $user
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function edit(User $user)
    {
        $roles = ['buyer', 'seller', 'moderator', 'admin'];

        $user->username = $this->username;
        $user->reference_code = $this->reference_code;
        $user->fe = $this->finalizearly;

        $rolesInput = !empty($this->role) ? $this->role : ['buyer'];

        if (!in_array('seller', $rolesInput)) {
            $user->seller = false;

            #Takes all user products and deletes them
            $sellerProducts = Product::where('seller_id', $user->id)->get();
            foreach ($sellerProducts as $product) {
                $product->featured = false;
                $product->deleted = true;
                $product->save();

                #Removes product from users' favorites
                $allFavorites = Favorite::where('product_id', $product->id)->get();

                foreach ($allFavorites as $favorite) {
                    $favorite->delete();
                }
            }
        }

       if (!in_array('moderator', $rolesInput)) {
            $user->moderator = false;
        }

       if (!in_array('admin', $rolesInput)) {
            $user->admin = false;
        }

        foreach ($rolesInput as $role) {
            if (!in_array($role, $roles)) {
                throw new \Exception("Unable to add the title of $role to the user!");
            }

            if ($role == 'seller')
                $user->seller = true;
                $user->seller_since = Carbon::now();
            if ($role == 'moderator')
                $user->moderator = true;
            if ($role == 'admin')
                $user->admin = true;
        }

        $user->save();

        session()->flash('success', 'User '.$user->username.' changed successfully!');
        return redirect()->route('admin.user', ['user' => $user->id]);
    }
}

<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\{Product,Image};

class ImageRequest extends FormRequest
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
            'new_image' => 'required|image|mimes:jpeg,jpg,png|dimensions:min_width=96,min_height=96|max:5000'
        ];
    }

    /**
     * Convert the uploaded image to base64
     * 
     * @return function|imageBase64
     */
    private function conversorImage()
    {
        $image = $this->new_image->store('img'); #Save product image
        
        $path = $_SERVER['DOCUMENT_ROOT']."/storage/$image"; #Take the product image path
        $type = pathinfo($path, PATHINFO_EXTENSION); #Get product image type
        $data = file_get_contents($path); #Get the product image
        $imageBase64 = "data:image/$type;base64,".base64_encode($data); #Convert product image to base64

        Storage::delete($image); #Delete the product image from the server as it is no longer needed

        return $imageBase64;
    }

    /**
     * Check if the product has already reached the maximum amount of image
     * @param $p
     */
    private function checkQuantityOfImages($p)
    {
        $totalImages = $p;

        if ($totalImages >= 5) {
            throw new \Exception('Only 5 images are allowed per product!');
        }
    }

    /**
     * Add product image
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function add()
    {
        $this->checkQuantityOfImages(session()->has('images') ? count(session()->get('images')) : 0);

        #Creates a new array in the images session containing the uploaded image information
        session()->push('images', [
            'image' => $this->conversorImage(), #Stores the base64 code of the image
            'uuid' => Str::uuid() #Generate a UUID for the image stored in the session
        ]);

        session()->flash('success', 'Image successfully added!');
        return redirect()->back();
    }

    /**
     * Edit product image HTTP respost
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function edit(Product $product)
    {
        $this->checkQuantityOfImages(count($product->images));

        $image = new Image();
        $image->id = Str::uuid();
        $image->product_id = $product->id; #Takes the ID of the product being edited and saves
        $image->image = $this->conversorImage();
        $image->save();

        session()->flash('success', 'Image successfully added!');
        return redirect()->route('images', ['section' => 'edit', 'product' => $product->id]);
    }
}

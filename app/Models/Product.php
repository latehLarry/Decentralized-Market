<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUIDs;

class Product extends Model
{
    use HasFactory, UUIDs;

    /**
     * Returns the product owner/seller
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }

    /**
     * Get the category the product belongs to
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Return all product images
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
    	return $this->hasMany(Image::class, 'product_id', 'id');
    }

    /**
     * Return all offers
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offers()
    {
        return $this->hasMany(Offer::class, 'product_id', 'id')->where('deleted', false);
    }
    
    /**
     * Return all delivery methods
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'product_id', 'id')->where('deleted', false);
    }

    /**
     * Returns the first image in the collection
     * 
     * @return App\Models\Image
     */
    public function featuredImage()
    {
    	$firstImage = $this->images()->orderBy('created_at')->first();

        return $firstImage->image;
    }

    /**
     * Take the offer with the lowest price
     * 
     * @return float
     */
    public function from()
    {
        $offer = $this->offers()->orderBy('price', 'ASC')->first();
        
        return $offer->price;
    }

    /**
     * Returns the country of origin of the product
     * 
     * @return string
     */
    public function shipsFrom()
    {
        if (in_array($this->ships_from, array_keys(config('countries')))) {
            return config('countries.'.$this->ships_from);  
        } else {
            return 'Undefined';
        }
    }

    /**
     * Returns the product delivery location
     * 
     * @return string
     */
    public function shipsTo()
    {
        if (in_array($this->ships_to, array_keys(config('countries')))) {
            return config('countries.'.$this->ships_to);  
        } elseif ($this->ships_to == 'WWW') {
            return 'World wide';
        } else {
            return 'Undefined';
        }
    }

    /**
     * Get all product feedbacks
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'product_id', 'id')->where('message', '!=', '');
    }
}

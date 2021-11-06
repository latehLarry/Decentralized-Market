<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUIDs;

class Dispute extends Model
{
    use HasFactory, UUIDs;

    /**
     * Take the disputed order
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * Take the product
     *  
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product()
    {
    	return $this->hasOne(Product::class, 'id', 'product_id');
    }

    /**
     * Take the buyer
     *  
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function buyer()
    {
    	return $this->hasOne(User::class, 'id', 'buyer_id');
    }

    /**
     * Take the seller
     *  
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function seller()
    {
    	return $this->hasOne(User::class, 'id', 'seller_id');
    }

    /**
     * Take the winner
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function winner()
    {
    	return $this->hasOne(User::class, 'id', 'winner_id');
    }

    /**
     * Get the dispute messages
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(DisputeMessage::class, 'dispute_id', 'id')->orderBy('created_at', 'DESC')->paginate(15);
    }
}

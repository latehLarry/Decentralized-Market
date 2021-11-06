<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    
    public $incrementing = false;
    public $keyType = 'string'; 

    /**
     * Returns the product that owns the delivery method
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
    	return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}

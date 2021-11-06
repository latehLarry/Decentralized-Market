<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $keyType = 'string'; 

    /**
     * Returns the product that owns the image
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
    	return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}

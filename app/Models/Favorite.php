<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUIDs;

class Favorite extends Model
{
    use HasFactory, UUIDs;

    /**
     * Returns favorite product
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
    	return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}

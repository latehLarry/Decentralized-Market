<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUIDs;

class Fan extends Model
{
    use HasFactory, UUIDs;

    public $timestamps = false;

    /**
     * the seller that the user is a fan
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
    	return $this->belongsTo(User::class, 'seller_id', 'id');
    }
}

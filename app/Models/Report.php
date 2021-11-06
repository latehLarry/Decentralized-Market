<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use App\Traits\UUIDs;

class Report extends Model
{
    use HasFactory, UUIDs;

    /**
     * Decrypt message
     * 
     * @return Illuminate\Support\Facades\Crypt
     */
    public function decryptMessage()
    {
        return Crypt::decryptString($this->message);
    }

    /**
     * Get the user who owns the message
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'issuer_id', 'id');
    }

    /**
     * Get the reported product
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
    	return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}

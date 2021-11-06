<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\UUIDs;

class Feedback extends Model
{
    use HasFactory, UUIDs;

    /**
     * Get the update date
     * 
     * @return Carbon\Carbon
     */
    public function freshness()
    {
    	return Carbon::parse($this->updated_at)->diffForHumans();
    }

    /**
     * Get the user who created the feedback
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Hide a part of the username
     * 
     * @return string
     */
    public function hiddenUser()
    {
        $user = User::find($this->buyer_id);

    	$username = $user->username;

    	#View first part of username
    	$firstUsername = substr($username,0, 3);

    	return $firstUsername.'***';
    }

    /**
     * Get the proprietary product
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get the purchase that this feedback belongs to
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}

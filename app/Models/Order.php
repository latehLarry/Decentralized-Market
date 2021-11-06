<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\UUIDs;

class Order extends Model
{
    use HasFactory, UUIDs;

    /**
     * [product description]
     * @return [type] [description]
     */
    public function product()
    {
    	return $this->hasOne(Product::class, 'id', 'product_id');
    }

    /**
     * [seller description]
     * @return [type] [description]
     */
    public function seller()
    {
    	return $this->hasOne(User::class, 'id', 'seller_id');
    }

    /**
     * [buyer description]
     * @return [type] [description]
     */
    public function buyer() 
    {
    	return $this->hasOne(User::class, 'id', 'buyer_id');
    }

    /**
     * See if the authenticated user is the seller
     * 
     * @return bool
     */
    public function isSeller() : bool
    {
        $user = auth()->user();

        return $user->id == $this->seller->id;
    }

    /**
     * See if the authenticated user is the buyer
     * 
     * @return bool
     */
    public function isBuyer() : bool
    {
        $user = auth()->user();

        return $user->id == $this->buyer->id;
    }

    /**
     * Check if the current status of the order is "waiting"
     * 
     * @return bool
     */
    public function waiting() : bool
    {
        return $this->status == 'waiting';
    }

    /**
     * Check if the current status of the order is "accepted"
     * 
     * @return bool
     */
    public function accepted() : bool
    {
        return $this->status == 'accepted';
    }

    /**
     * Check if the current status of the order is "shipped"
     * 
     * @return bool
     */
    public function shipped() : bool
    {
        return $this->status == 'shipped';
    }

    /**
     * Check if the current status of the order is "delivered"
     * 
     * @return bool
     */
    public function delivered() : bool
    {
        return $this->status == 'delivered';
    }

    /**
     * Check if the current status of the order is "canceled"
     * 
     * @return bool
     */
    public function canceled() : bool
    {
        return $this->status == 'canceled';
    }

    /**
     * Check if the current status of the order is "disputed"
     * 
     * @return bool
     */
    public function disputed() : bool
    {
        return $this->status == 'disputed';
    }

    /**
     * Returns the amount of monero that the deposit address received
     *
     * @return float
     */
    public function totalReceived()
    {
        try {
            return \Monerod::getTotalReceived($this->escrow_monero_wallet);
        } catch (\Exception $exceoption) {
            return 0.00000;
        }
    }

    /**
     * Check if the order has been paid or not
     *
     * @return bool
     */
    public function paidOrder()
    {
        if ($this->totalReceived() >= $this->total_in_monero)
            return true;

        return false;
    }

    /**
     * Returns the dispute pertaining to the order
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dispute()
    {
        return $this->hasOne(Dispute::class, 'order_id', 'id');
    }

    /**
     * Returns the feedback pertaining to the order
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function feedback()
    {
        return $this->hasOne(feedback::class, 'order_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use App\Traits\UUIDs;
use App\Models\Conversation;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, UUIDs;

    /**
     * The attributes that should be hidden for serialization
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'pin',
        'remember_token',
    ];

    /**
     * Check if the user is a seller
     * 
     * @return boolean 
     */
    public function isSeller() : bool
    {
        return $this->seller == true ? true : false;
    }

    /**
     * Check if the user is a admin
     * 
     * @return boolean 
     */
    public function isAdmin() : bool
    {
        return $this->admin == true ? true : false;
    }

    /**
     * Check if the user is a moderator
     * 
     * @return boolean 
     */
    public function isModerator() : bool
    {
        return $this->moderator == true ? true : false;
    }

    /**
     * User conversations
     * 
     * @return App\Models\Conversation
     */
    public function conversations()
    {
        return Conversation::where('issuer_id', $this->id)->orWhere('receiver_id', $this->id);
    }

    /**
     * User orders
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id', 'id')
                    ->where('deleted', false)
                    ->orderBy('created_at', 'DESC');
    }

    /**
     * User's favorite products
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id', 'id');
    }

    /**
     * Return all user's favorite sellers
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sellers()
    {
        return $this->hasMany(Fan::class, 'fan_id', 'id');
    }

    /**
     * User fans (seller)
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fans()
    {
        return $this->hasMany(Fan::class, 'seller_id', 'id');
    }

    /**
     * User help requests
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function helpRequests()
    {
        return $this->hasMany(HelpRequest::class, 'user_id', 'id')->orderBy('created_at', 'DESC');
    }

    /**
     * User transition records
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transitions()
    {
        return $this->hasMany(Transition::class, 'user_id', 'id')->orderBy('created_at', 'DESC');
    }

    /**
     * User sales (seller)
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales()
    {
        return $this->hasMany(Order::class, 'seller_id', 'id')
                    ->where('deleted', false)
                    ->orderBy('created_at', 'DESC');
    }

    /**
     * Products not deleted from the user (seller)
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id', 'id')->where('deleted', false);
    }

    /**
     * Feedback the user received (seller)
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'seller_id', 'id')->where('message', '!=', '');
    }

    /**
     * Get the affiliate that referred you to the market
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reference()
    {
        return $this->belongsTo(User::class, 'referenced_by', 'id');
    }

    /**
     * Return all user notifications
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id');
    }

    /**
     * Returns the number of user notifications
     * 
     * @return int
     */
    public function totalUnreadNotifications()
    {   
        return $this->notifications()->where('read', false)->count();
    }

    /**
     * Returns the number of user favorites
     * 
     * @return int
     */
    public function totalFavorites()
    {
        return $this->favorites()->count();
    }

    /**
     * Returns the number of fans of the user
     * 
     * @return int
     */
    public function totalFans()
    {
        return $this->fans()->count();
    }

    /**
     * Returns the number of orders with the status equivalent to the parameter passed
     * @param  $status
     * 
     * @return int
     */
    public function totalOrders($status)
    {
        return $this->orders()->where('status', $status)->count();
    }

    /**
     * Total unread messages user
     * 
     * @return int
     */
    public function totalUnreadMessages()
    {
        #Grabs all user conversations
        $conversations = $this->conversations()->get();

        #Sets the variable that will save the total of unread messages
        $total = 0;

        foreach ($conversations as $conversation) {
            $total += $conversation->unreadMessages();
        }

        return $total;
    }

    /**
     * Count how many non-empty feedbacks the user received (seller)
     * @param  $type 
     * 
     * @return int
     */
    public function totalFeedbacks($type = null)
    {
        if (!is_null($type)) {
            return $this->feedbacks()->where('type', $type)
                                     ->where('message', '!=', '')
                                     ->count();
        }
    
        return $this->feedbacks()->count();
    }

    /**
     * Returns the number of sales with the status equivalent to the parameter passed (seller)
     * 
     * @param  $status
     * @return int
     */
    public function totalSales($status)
    {
        return $this->sales()->where('status', $status)->count();
    }

    /**
     * Ghost user
     * 
     * @return App\Models\User
     */
    public function ghostUser()
    {
        $ghost = new User();
        $ghost->username = 'STAFF MESSAGE';

        return $ghost;
    }

    /**
     * Check if the user has favorited the product passed as a parameter
     * @param  Product $product 
     * 
     * @return boolean 
     */
    public function isFavorite(Product $product) : bool
    {
        #Get favorite
        $favorite = Favorite::where('product_id', $product->id)
                            ->where('user_id', $this->id)
                            ->first();

        if (!is_null($favorite)) {
            return true;
        }
        
        return false;
    }

    /**
     * Check if the user is a fan of the seller passed as parameter
     * 
     * @param  User $seller
     * @return bool
     */
    public function isFan(User $seller) : bool
    {
        #Check
        $fan = Fan::where('seller_id', $seller->id)
                  ->where('fan_id', $this->id)
                  ->first();

        if (!is_null($fan)) {
            return true;
        }

        return false;
    }

    /**
     * Get five random products from the user (seller)
     * 
     * @return App\Models\Product
     */
    public function randomListings()
    {
        return $this->products()->inRandomOrder()
                                ->limit(4)
                                ->get();
    }

    /**
     * Get the last time the user authenticates to the market
     * 
     * @return Carbon/Carbon
     */
    public function lastLogin()
    {
        return Carbon::parse($this->last_login)->diffForHumans();
    }

    /**
     * Get the date the user became a seller (seller)
     * 
     * @return Carbon\Carbon
     */
    public function sellerSince()
    {
        return Carbon::parse($this->seller_since)->diffForHumans();
    }

    /**
     * Get the date the user registered on the market
     * 
     * @return Carbon\Carbon
     */
    public function memberSince()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    /**
     * Get user balance
     * 
     * @return float
     */
    public function balance()
    {
        return \Monerod::getBalance($this->id);
    }

    /**
     * Get the total completed orders by status
     * @param  $mounths
     * 
     * @return int
     */
    public function totalOrdersCompleted($mounths = null)
    {
        $total = $this->orders()->where('status', 'delivered')->count();

        if (!is_null($mounths)) {
            $total = $this->orders()->where('status', 'delivered')
                                    ->where('created_at', '>=', Carbon::now()->subMonth($mounths))
                                    ->where('created_at', '<=', Carbon::now())
                                    ->count();
        }

        return $total;
    }

    /**
     * Get the percentage of positive feedbacks received (seller)
     * 
     * @return float
     */
    public function ratePositiveFeedbacks()
    {
        $totalFeedbacks = $this->totalFeedbacks();
        $totalPositiveFeedbacks = $this->totalFeedbacks('positive');

        $rate = $totalFeedbacks > 0 ? ($totalPositiveFeedbacks*100)/$totalFeedbacks : 0;

        return number_format($rate, 2).'%';
    }

    /**
     * Calculation of total user spend over a period of time
     * @param  User   $user 
     * @param  $mounths
     * 
     * @return float
     */
    public function totalSpent(User $user, $mounths = null)
    {
        $orders = \DB::table('orders')->select('total')
                                      ->where('buyer_id', $user->id)
                                      ->where('status', 'delivered')
                                      ->get();

        $totalSpent = $orders->sum('total');

        if (!is_null($mounths)) {
            $orders = \DB::table('orders')->select('total')
                                          ->where('created_at', '>=', Carbon::now()->subMonth($mounths))
                                          ->where('created_at', '<=', Carbon::now())
                                          ->where('buyer_id', $user->id)
                                          ->where('status', 'delivered')
                                          ->get();

            $totalSpent = $orders->sum('total');
        }

        return number_format($totalSpent, 5);
    }

    /**
     * Gets the percentage of user disputes over a period of time
     * @param  $mounths
     * 
     * @return float
     */
    public function rateDispute($mounths = null)
    {
        $totalOrders = $this->totalOrdersCompleted($mounths);
        $totalDispute = $this->totalOrders('disputed');

        if (!is_null($mounths)) {
            $totalDispute = $this->orders()->where('status', 'disputed')
                                           ->where('created_at', '>=', Carbon::now()->subMonth($mounths))
                                           ->where('created_at', '<=', Carbon::now())
                                           ->count();
        }

        $rate = $totalOrders > 0 ? $totalDispute*100/$totalOrders : 0;

        return number_format($rate, 2).'%';
    }

    /**
     * Check if the user has FE enabled (seller)
     * 
     * @return bool
     */
    public function finalizeEarly() : bool
    {
        if ($this->fe == true) {
            return true;
        }

        return false;
    }

    /**
     * Total won disputes (seller)
     * 
     * @return int
     */
    public function wonDisputes()
    {
        $totalWonDusputes = Dispute::where('seller_id', $this->id)
                                    ->where('winner_id', $this->id)
                                    ->count();

        return $totalWonDusputes;
    }

    /**
     * Total disputes (seller)
     * 
     * @return int
     */
    public function totalDisputes()
    {
        $totalDisputes = Dispute::where('seller_id', $this->id)
                                ->count();

        return $totalDisputes;
    }
}

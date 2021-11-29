<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\{Product,User,Conversation,Order,HelpRequest,Feedback};

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('conversation', function(User $user, Conversation $conversation) {
            return $user->id == $conversation->issuer_id || $user->id == $conversation->receiver_id;
        });

        Gate::define('product', function(User $user) {
            return $user->isSeller() || $user->isModerator() || $user->isAdmin() && $order->deleted == false;
        });

        Gate::define('update-product', function(User $user, Product $product) {
            return $user->id == $product->seller_id || $user->isModerator() || $user->isAdmin() && $product->deleted == false;
        });

        Gate::define('order', function(User $user, Order $order) {
            return $user->id == $order->buyer_id || $user->id == $order->seller_id || $user->isModerator() || $user->isAdmin() && $order->deleted == false;
        });

        Gate::define('help-request', function(User $user, HelpRequest $helpRequest) {
            return $user->id == $helpRequest->user_id || $user->isModerator() || $user->isAdmin();
        });

        Gate::define('feedback', function(User $user, Feedback $feedback) {
            return $user->id == $feedback->buyer_id;
        });

        Gate::define('finalizearly', function(User $user, Order $order) {
            return $user->id == $order->buyer_id && $order->shipped() && $order->buyer->finalizearly() && $order->deleted == false;
        });
    }
}

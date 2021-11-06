<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Tools\Monerod;

class MonerodServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('monerod', function() {
            return new Monerod();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

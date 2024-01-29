<?php

namespace App\Providers;

use App\Services\SubscriptionService;
use Illuminate\Support\ServiceProvider;

class SubscriptionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(SubscriptionService::class, function () {
            return new SubscriptionService();
        });
    }


    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

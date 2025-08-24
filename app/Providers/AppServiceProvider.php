<?php

namespace App\Providers;

use App\Broadcasting\PusherBroadcast;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PusherBroadcast::class, function ($app) {
            return new PusherBroadcast();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

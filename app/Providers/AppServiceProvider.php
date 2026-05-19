<?php

namespace App\Providers;

use App\Models\OrderBlood;
use App\Models\OrderBloodDetail;
use App\Models\OrderLogActivity;
use App\Observers\OrderBloodDetailObserver;
use App\Observers\OrderBloodObserver;
use App\Observers\OrderLogActivityObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ---------- Register Observer ----------
        OrderBlood::observe(OrderBloodObserver::class);
        OrderBloodDetail::observe(OrderBloodDetailObserver::class);
        OrderLogActivity::observe(OrderLogActivityObserver::class);
    }
}

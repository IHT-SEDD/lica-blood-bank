<?php

namespace App\Providers;

use App\Models\OrderBlood;
use App\Models\OrderBloodDetail;
use App\Models\OrderLogActivity;
use App\Observers\OrderBloodDetailObserver;
use App\Observers\OrderBloodObserver;
use App\Observers\OrderLogActivityObserver;
use Dedoc\Scramble\Scramble;
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

        // ---------- Register API for Scramble ----------
        Scramble::registerApi('v1', [
            'info' => [
                'version' => '1.0'
            ],
            'api_path' => 'api/v1',
        ]);
    }
}

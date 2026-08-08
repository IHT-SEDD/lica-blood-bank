<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(config_path('api/blood-component.php'), 'api.blood-component');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ---------- Register API for Scramble ----------
        Scramble::registerApi('v1', [
            'info' => [
                'version' => '1.0'
            ],
            'api_path' => 'api/v1',
        ]);
    }
}

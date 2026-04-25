<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader; // <--- WAJIB ADA
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\URL;

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
        $loader = AliasLoader::getInstance();
        $loader->alias('QrCode', QrCode::class);
        URL::forceScheme('https');
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader; // <--- WAJIB ADA
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
    }
}

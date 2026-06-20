<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Forzamos HTTPS en producción para evitar mixed content
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}

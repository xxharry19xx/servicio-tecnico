<?php

namespace App\Providers;

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
        // Registramos el alias de QrCode para usarlo en las vistas
        $this->app->alias(
            \SimpleSoftware\QrCode\Facades\QrCode::class,
            'QrCode'
        );
    }
}

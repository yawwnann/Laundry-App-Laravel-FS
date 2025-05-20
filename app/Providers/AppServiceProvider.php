<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract; // Import Kontrak
use App\Http\Responses\FilamentLogoutResponse; // Import Implementasi Anda

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Binding implementasi LogoutResponse kustom kita ke kontrak Filament
        $this->app->singleton(
            LogoutResponseContract::class,
            FilamentLogoutResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
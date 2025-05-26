<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\Paginator; // Import the Paginator facade
use App\Http\Middleware\RoleMiddleware;

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
        // Set the default string length for migrations
        Schema::defaultStringLength(191);

        // Register the 'role' middleware alias
        Route::aliasMiddleware('role', \App\Http\Middleware\RoleMiddleware::class);

        // Use Bootstrap for pagination
        Paginator::useBootstrap();
    }
}
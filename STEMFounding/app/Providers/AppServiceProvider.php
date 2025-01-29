<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\InvestmentController;

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
        $this->app['router']->aliasMiddleware('role', \App\Http\Middleware\RoleMiddleware::class);
        (new InvestmentController)->processRefunds();
    }
}

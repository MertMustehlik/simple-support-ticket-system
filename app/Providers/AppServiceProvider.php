<?php

namespace App\Providers;

use App\Interfaces\AuthInterface;
use App\Interfaces\TicketInterface;
use App\Services\AuthService;
use App\Services\TicketService;
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

        $this->app->bind(AuthInterface::class, AuthService::class);
        $this->app->bind(TicketInterface::class, TicketService::class);
    }
}

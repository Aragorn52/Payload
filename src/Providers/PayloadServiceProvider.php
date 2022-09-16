<?php

namespace PayloadLumenPassport\Providers;

use Illuminate\Support\ServiceProvider;
use PayloadLumenPassport\Services\AccessTokenService;

use function app;

class PayloadServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AccessTokenService::class, fn () => new AccessTokenService());
    }
}
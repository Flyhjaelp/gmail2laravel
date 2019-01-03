<?php

namespace Flyhjaelp\Gmail2Laravel;

use Illuminate\Support\ServiceProvider;

class Gmail2LaravelServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(Gmail2Laravel::class, function ($app, $arg) {

            return new Gmail2Laravel($arg);

        });

        $this->app->alias(Gmail2Laravel::class, 'Gmail2Laravel');

    }

        /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Gmail2Laravel'];
    }

}

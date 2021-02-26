<?php

namespace KTL\Sigma\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use KTL\Sigma\Sigma;

class SigmaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('sigma', function (){
            return new Sigma();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Inertia::setRootView('sigma/sigma');

        /* Load Sigma routes */
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        /* Load Sigma views */
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'sigma');

        /* publish sigma views */
        $this->publishes([
            __DIR__.'/../Resources/views' => resource_path('views/sigma'),
        ], 'views');

        /* publish sigma configs */
        $this->publishes([
            __DIR__ . '/../Config/sigma.php' => config_path('sigma.php'),
        ], 'config');

        /* publish sigma assets */
        $this->publishes([
            __DIR__.'/../Resources/assets' => public_path('vendor/sigma')
        ], 'public');

        /* load sigma migrations */
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');

        /* publish sigma migrations */
        $this->publishes([
            __DIR__.'/../Database/migrations/' => database_path('migrations')
        ], 'migrations');
    }
}

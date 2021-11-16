<?php

namespace App\Providers;

use App\Lib\Sigma\SigmaConnector;
use Illuminate\Support\ServiceProvider;

class SigmaProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('sigma', function (){
            return new SigmaConnector();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

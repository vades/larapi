<?php

namespace App\Lib\Auth;

use Illuminate\Support\ServiceProvider;

use App\Lib\Auth\AuthJwt;

class AuthJwtServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('authjwt',function(){

            return new AuthJwt();
    
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

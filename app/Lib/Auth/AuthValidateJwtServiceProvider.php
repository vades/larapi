<?php

namespace App\Lib\Auth;

use Illuminate\Support\ServiceProvider;

use App\Lib\Auth\AuthValidateJwt;

class AuthValidateJwtServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('authvalidatejwt',function(){

            return new AuthValidateJwt();
    
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

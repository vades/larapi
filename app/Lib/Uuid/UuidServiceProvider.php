<?php

namespace App\Lib\Uuid;

use Illuminate\Support\ServiceProvider;

use App\Lib\Uuid\Uuid;

class UuidServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('uuid',function(){

            return new Uuid();
    
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

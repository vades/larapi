<?php
namespace App\Lib\Auth;
use Illuminate\Support\Facades\Facade;

class AuthJwtFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'authjwt';
    }
}
<?php
namespace App\Lib\Auth;
use Illuminate\Support\Facades\Facade;

class AuthValidateJwtFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'authvalidatejwt';
    }
}
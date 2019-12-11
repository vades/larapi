<?php
namespace App\Lib\Uuid;
use Illuminate\Support\Facades\Facade;

class UuidFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'uuid';
    }
}
<?php

namespace Noisim\Hubstaff\Facades;

use Illuminate\Support\Facades\Facade;

class User extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'hs-user';
    }
}
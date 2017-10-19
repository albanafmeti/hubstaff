<?php

namespace Noisim\Hubstaff\Facades;

use Illuminate\Support\Facades\Facade;

class Project extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'hs-project';
    }
}
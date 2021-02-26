<?php


namespace KTL\Sigma\Facade;

use Illuminate\Support\Facades\Facade;

class Sigma extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'sigma';
    }
}

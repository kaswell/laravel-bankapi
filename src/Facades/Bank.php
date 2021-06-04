<?php

namespace Kaswell\Bank\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Bank
 * @package Kaswell\Bank\Facades
 */
class Bank extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Bank';
    }
}

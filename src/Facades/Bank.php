<?php

namespace Kaswell\Bank\Facades;

use Illuminate\Support\Facades\Facade;
use phpDocumentor\Reflection\Types\Static_;

/**
 * Class Bank
 * @package Kaswell\Bank\Facades
 * @method static array getCurrency(int $cur_id = ZERO)
 * @method static array getCurrencyRate(int $cur_id = ZERO, $date = null)
 */
class Bank extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Bank';
    }
}

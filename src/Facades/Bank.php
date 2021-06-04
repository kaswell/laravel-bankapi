<?php

namespace Kaswell\Bank\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Bank
 * @package Kaswell\Bank\Facades
 * @method static array getCurrencies()
 * @method static array getCurrencyById(int $cur_id = ZERO)
 * @method static array getCurrenciesRates($date = null, int $periodicity = 0, int $paramMode = 0)
 * @method static array getCurrencyRate($cur_id, $date = null, int $periodicity = 0, int $paramMode = 0)
 */
class Bank extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Bank';
    }
}

<?php

namespace Kaswell\Bank;

use Illuminate\Support\ServiceProvider;

/**
 * Class BankServiceProvider
 * @package Kaswell\Bank
 */
class BankServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/bank.php' => config_path('bank.php'),
        ], 'config');

        $this->app->singleton(Errors::class, function (){
            return Errors::getInstance();
        });
    }


    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/bank.php', 'bank'
        );

        $this->app->bind('Bank', function (){
            return new \Kaswell\Bank\Bank;
        });
    }
}
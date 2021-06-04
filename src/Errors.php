<?php

namespace Kaswell\Bank;

/**
 * Class Errors
 * @package Kaswell\Bank
 */
class Errors
{
    /**
     * @var Errors $instance
     */
    private static $instance;

    /**
     * @return Errors
     */
    public static function getInstance(): Errors
    {
        if (is_null(static::$instance) || !(static::$instance instanceof Errors)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    private function __clone(){}
    private function __construct(){}

    /**
     * @var array
     */
    private $errors = EMPTY_ARRAY;


    /**
     * @param array $error
     */
    public function set(array $error)
    {
        $this->errors[] = $error;
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->errors;
    }
}
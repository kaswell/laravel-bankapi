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

    /**
     * @return void
     */
    private function __clone()
    {
        if (is_null(static::$instance) || !(static::$instance instanceof Errors)) {
            static::$instance = new static;
        }
    }

    /**
     * Errors constructor.
     * @return void
     */
    private function __construct()
    {
        if (is_null(static::$instance) || !(static::$instance instanceof Errors)) {
            static::$instance = new static;
        }
    }

    /**
     * @var array
     */
    private $errors = [];


    /**
     * @param array $error
     * @return void
     */
    public function add(array $error)
    {
        $this->errors[] = $error;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function empty(): bool
    {
        return !(count($this->errors) > 0);

    }
}
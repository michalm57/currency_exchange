<?php

namespace App\Core;

class App
{
    /**
     * @var array $registry
     */
    protected static $registry = [];

    /**
     * Bind a value to the container.
     *
     * @param string $key The key or identifier for the value.
     * @param mixed $value The value to be bound.
     * @return void
     */
    public static function bind($key, $value)
    {
        static::$registry[$key] = $value;
    }

    /**
     * Get a value from the container.
     *
     * @param string $key The key or identifier of the value to retrieve.
     * @return mixed The retrieved value.
     * @throws Exception If the requested key is not found in the container.
     */
    public static function get($key)
    {
        if (!array_key_exists($key, static::$registry)) {
            throw new Exception('No {$key} is bound in the container!');
        }
        return static::$registry[$key];
    }
}

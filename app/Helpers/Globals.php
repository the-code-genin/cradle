<?php

namespace App\Helpers;

/**
 * This helper class registers global objects.
 */
class Globals
{
    /** @var array $objects The global objects */
    private static $objects = [];

    /**
     * Get the value for global object
     *
     * @param string $name
     * @return mixed
     */
    public static function get(string $name)
    {
        return self::$objects[$name];
    }

    /**
     * Set the value of a global object
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public static function set(string $name, $value)
    {
        self::$objects[$name] = $value;
    }

    /**
     * Remove a global object
     *
     * @param string $name
     * @return void
     */
    public static function remove(string $name)
    {
        unset(self::$objects[$name]);
    }

    /**
     * Check if a global object is set
     *
     * @param string $name
     * @return boolean
     */
    public static function isset(string $name): bool
    {
        return isset(self::$objects[$name]);
    }
}

<?php
namespace Iyiola\Util;

/**
 * This class helps manage session data
 */
class Session
{ 
	/**
     * Destroys a session and unsets all session variables
     */
    public static function close(): void
    {
        $_SESSION = [];
        session_destroy();
    }
     
	/**
	 * Checks if a session variable has been set
	 */
    public static function exists(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
     
    /**
     * Gets a session variable
     */
    public static function get(string $key): ?string
    {
        if (!isset($_SESSION[$key])) {
            return null;
        }

        return $_SESSION[$key];
    }
	
    /**
     * Sets a session variable
     */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }
     
    /**
     * Removes a session variable
     */
    public static function remove(string $key): void
    {
		if (isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
		}
    }
}

<?php
namespace Iyiola\Util;

/**
 * This class helps manage cookie data
 */
class Cookie
{             
    /**
     * Checks if a cookie has been set
     */
    public static function exists(string $key): bool
    {
        return isset($_COOKIE[$key]);
    }
     
    /**
     * Gets a cookie
     */
    public static function get(string $key): ?string
    {
        if (!isset($_COOKIE[$key])) {
			return null;
		}

		return $_COOKIE[$key];
    }
    
    /**
     * Sets a cookie
     */
    public static function set(string $key, string $value, int $expires = null, bool $http_only = true): void
    {
		if (!isset($expires) | $expires < 0) {
			$expires = time() + (14*24*60*60);
		}

		setcookie($key, $value, $expires, '/', $_SERVER['HTTP_HOST'], isset($_SERVER['HTTPS']), $http_only);
    }
     
    /**
     * Removes a cookie
     */
    public static function remove(string $key): void
    {
		setcookie($key, ' ', time() - 3600, '/', $_SERVER['HTTP_HOST'], isset($_SERVER['HTTPS']), true);
    }
}

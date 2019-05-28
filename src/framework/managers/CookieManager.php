<?php
namespace Cradle\Framework\Managers;

/**
 * This class helps manage cookie data.
 */
class CookieManager
{
    /**
     * Checks if a cookie was sent.
     */
    public function exists(string $key): bool
    {
        return isset($_COOKIE[$key]);
    }

    /**
     * Gets a cookie.
     */
    public function get(string $key): ?string
    {
        if (!isset($_COOKIE[$key])) {
			return null;
		}

		return $_COOKIE[$key];
    }

    /**
     * Gets all cookies.
     */
    public function getAll(): array
    {
        return $_COOKIE;
    }
    
    /**
     * Sets a cookie.
     */
    public function set(string $key, string $value, int $expires = null, bool $http_only = true): void
    {
		if (!isset($expires) | $expires < 0) {
			$expires = time() + (14*24*60*60);
		}

		setcookie($key, $value, $expires, '/', $_SERVER['HTTP_HOST'], isset($_SERVER['HTTPS']), $http_only);
    }

    /**
     * Removes a cookie.
     */
    public function remove(string $key): void
    {
		setcookie($key, ' ', time() - 3600, '/', $_SERVER['HTTP_HOST'], isset($_SERVER['HTTPS']));
    }

    /**
     * Removes all cookies.
     */
    public function removeAll(): void
    {
        foreach ($this->getAll() as $key => $value) {
            $this->remove($key);
        } 
    }
}

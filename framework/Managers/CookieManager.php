<?php
namespace Cradle\Managers;

/**
 * This class helps manage cookie data.
 */
class CookieManager
{
    /**
     * Checks if a cookie was sent.
     * 
     * @param string $key The key for the cookie
     * 
     * @return bool The existence status of the cookie
     */
    public function exists(string $key): bool
    {
        return isset($_COOKIE[$key]);
    }

    /**
     * Gets a cookie.
     * 
     * @param string $key The key for the cookie
     * 
     * @return string|null The value of the cookie
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
     * 
     * @return array An associative array containing all cookies sent to the server
     */
    public function getAll(): array
    {
        return $_COOKIE;
    }

    /**
     * Sets a cookie.
     * 
     * @param string $key The key for the cookie
     * @param string $value The cookie value
     * @param int $expires The cookie expiry time
     * @param bool $http_only Specifies if the cookie can only be accessed by the server
     * 
     * @return null
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
     * 
     * @param string $key The key for the cookie
     * 
     * @return null
     */
    public function remove(string $key): void
    {
		setcookie($key, ' ', time() - 3600, '/', $_SERVER['HTTP_HOST'], isset($_SERVER['HTTPS']));
    }

    /**
     * Removes all cookies.
     * 
     * @return null
     */
    public function removeAll(): void
    {
        foreach ($this->getAll() as $key => $value) {
            $this->remove($key);
        } 
    }
}

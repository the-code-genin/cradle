<?php
namespace Cradle\Framework\Managers;

/**
 * This class helps manage session data.
 */
class SessionManager
{
    // Stores the flash data sent from the last session
    private $flashData = [];

    // The variable responsible for storing flash data in the session
    private $flashVariable;

    public function __construct(string $flashVariable = '_SESSMANAGER_FLASHDATA')
    {
        // Extract flash data if it exists
        if ($this->exists($flashVariable)) {
            $this->flashData = $this->get($flashVariable);
        }

        // Reset the flash data
        $this->flashVariable = $flashVariable;
        $this->set($flashVariable, []);
    }

	/**
	 * Checks if a session variable has been set.
	 */
    public function exists(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
    
    /**
     * Checks if a flash variable has been set.
     */
    public function flashExists(string $key): bool
    {
        return isset($this->flashData[$key]);
    }

    /**
     * Gets a session variable.
     */
    public function get(string $key)
    {
        if (!isset($_SESSION[$key])) {
            return null;
        }

        return $_SESSION[$key];
    }

    /**
     * Gets a flash variable.
     */
    public function getFlash(string $key)
    {
        if (!isset($this->flashData[$key])) {
            return null;
        }

        return $this->flashData[$key];
    }
	
    /**
     * Gets all session variables.
     */
    public function getAll(): array
    {
        return $_SESSION;
    }

    /**
     * Gets all flash variables.
     */
    public function getAllFlash(): array
    {
        return $this->flashData;
    }

    /**
     * Sets a session variable.
     */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Sets a flash variable.
     */
    public function setFlash(string $key, $value): void
    {
        $_SESSION[$this->flashVariable][$key] = $value;
    }
     
    /**
     * Removes a session variable.
     */
    public function remove(string $key): void
    {
		if (isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
		}
    }

    /**
     * Removes a flash variable from the batch to be sent to the next request.
     */
    public function removeFlash(string $key): void
    {
        if (isset($_SESSION[$this->flashVariable][$key])) {
            unset($_SESSION[$this->flashVariable][$key]);
        }
    }

    /**
     * Removes all session variables.
     */
    public function empty(): void
    {
        $_SESSION = [];
    }

    /**
     * Removes all flash variables.
     */
    public function emptyFlash(): void
    {
        $_SESSION[$this->flashVariable] = [];
    }
}

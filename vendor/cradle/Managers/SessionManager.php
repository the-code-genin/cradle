<?php
namespace Cradle\Managers;

/**
 * This class helps manage session data.
 */
class SessionManager
{
    /** @var array $flashData Stores the flash data sent from the last session */
    private $flashData = [];

    /** @var string $flashVariable The variable responsible for storing flash data in the session */
    private $flashVariable;

    public function __construct(string $flashVariable = '_CRADLE_SESSION_MANAGER_FLASHDATA')
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
     * 
     * @param string $key The session variable key
     * 
     * @return bool Returns the session variable's existence status
	 */
    public function exists(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Checks if a flash variable has been set.
     * 
     * @param string $key The session flash variable key
     * 
     * @return bool Returns the session flash variable's existence status
     */
    public function flashExists(string $key): bool
    {
        return isset($this->flashData[$key]);
    }

    /**
     * Gets a session variable.
     * 
     * @param string $key The session variable's key
     * 
     * @return mixed The session variable's value
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
     * 
     * @param string $key The session flash variable's key
     * 
     * @return mixed The session flash variable's value
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
     * 
     * @return array An associative array of all the session's variables
     */
    public function getAll(): array
    {
        return $_SESSION;
    }

    /**
     * Gets all flash variables.
     * 
     * @return array An associative array of all the session's flash variables
     */
    public function getAllFlash(): array
    {
        return $this->flashData;
    }

    /**
     * Sets a session variable.
     * 
     * @param string $key The key to be used for the session variable
     * @param mixed $value The value of the session variable
     * 
     * @return null
     */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Sets a flash variable.
     * 
     * @param string $key The key to be used for the session flash variable
     * @param mixed $value The value of the session flash variable
     * 
     * @return null
     */
    public function setFlash(string $key, $value): void
    {
        $_SESSION[$this->flashVariable][$key] = $value;
    }

    /**
     * Removes a session variable.
     * 
     * @param string $key The session variable's key
     * 
     * @return null
     */
    public function remove(string $key): void
    {
		if (isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
		}
    }

    /**
     * Removes a flash variable from the batch to be sent to the next request.
     * 
     * @param string $key The session flash variable's key
     * 
     * @return null
     */
    public function removeFlash(string $key): void
    {
        if (isset($_SESSION[$this->flashVariable][$key])) {
            unset($_SESSION[$this->flashVariable][$key]);
        }
    }

    /**
     * Removes all session variables.
     * 
     * @return null
     */
    public function empty(): void
    {
        $_SESSION = [];
    }

    /**
     * Removes all flash variables.
     * 
     * @return null
     */
    public function emptyFlash(): void
    {
        $_SESSION[$this->flashVariable] = [];
    }
}

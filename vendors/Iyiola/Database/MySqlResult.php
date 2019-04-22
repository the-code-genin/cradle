<?php
namespace Iyiola\Database;

/**
 * Acts as a wrapper for a MySQL query result
 */
class MySqlResult
{
    // Holds the mysql result resource
    private $result;
    
    public function __construct(object $result)
    {
        if (!is_object($result)) {
            $this->result = null;
            return;
        }
        $this->result = $result;
    }

    /**
     * Fetch a record from the results as an array
     */
    public function fetchArray(): ?array
    {
        if (!isset($this->result) | !is_object($this->result)) {
            return null;
        }

        return mysqli_fetch_array($this->result);
    }

    /**
     * Fetch a record from the results as an associative array
     */
    public function fetchAssoc(): ?array
    {
        if (!isset($this->result) | !is_object($this->result)) {
            return null;
        }

        return mysqli_fetch_assoc($this->result);
    }
    
    /**
     * Fetch a record from the results as an object
     */
    public function fetchObject(): ?object
    {
        if (!isset($this->result) | !is_object($this->result)) {
            return null;
        }

        return mysqli_fetch_object($this->result);
    }
    
    /**
     * To get the number of rows in the result object
     */
    public function numRows(): int
    {
        if (!isset($this->result) | !is_object($this->result)) {
            return 0;
        }

        return mysqli_num_rows($this->result);
    }
    
    /**
     * Get the number if fields returned
     */
    public function numFields(): int
    {
        if (!isset($this->result) | !is_object($this->result)) {
            return 0;
        }

        return mysqli_num_fields($this->result);
    }

    /**
     * Free the result handler when the object is no longer needed
     */
    function __destruct()
    {
        if (!empty($this->result)) {
            mysqli_free_result($this->result);
        }
    }
}

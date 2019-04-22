<?php
namespace Iyiola\Database;

use MySqlResult;

class MySql
{
    // Holds the database connection
    private $conn;
    
    // Holds any mysql errors encountered
    private $error = '';
    
    // Stores the last executed query
    private $query = '';
    
    // Holds the recent MySqLResult object
    private $result;
    
    public function __construct(string $hostname, string $username, string $password, string $db)
    {
        // Connect to the database
        $conn = mysqli_connect($hostname, $username, $password, $db);
		
        // On failed db connection
        if (!$conn) {
            $this->error = mysqli_error($conn);
            $this->conn = null;
            return;
        }

        $this->conn = $conn;
        $this->error = '';
    }
     
    /**
     * Escapes any SQL meta characters in the argument
     */
    public function escape(string $string): string
    {
		$string = trim($string);
		if (!$this->is_connected()) {
			$this->error = 'Not connected to the database';
			return addslashes($string);
		}
        return mysqli_real_escape_string($this->conn, $string);
    }
    
    /**
     * Get the last error encountered
     */
    public function getError(): string
    {
        return $this->error;
    }
    
    /**
     * Get the last executed query
     */
    public function getQuery(): string
    {
        return $this->query;
    }
     
    /**
     * Get the result object
     */
    public function &getResult(): ?object
    {
        return $this->result;
    }
    
    /**
     * To check if the MySql object had any errors whether in connecting or after a query
     */
    public function hasError(): bool
    {
        if ($this->error == '') {
            return false;
        }

        return true;
    }
    
    /**
     * To check if the object is connected to the database
     */
    public function isConnected(): bool
    {
        return !empty($this->conn);
    }
    
    /**
     * Executes a sql query without returning a result object
     */
    public function exec(string $query): bool
    {
        // First check if the object is connected to the database
        if (!$this->is_connected()) {
            $this->error = 'Not connected to the database';
            return false;
        }
        
        // Store the query for future refrences
        $this->query = $query;
        
        // Execute the $query
        $result = mysqli_execute($this->conn, $query);

        // If query execution failed, store the error encountered
        if (!$result) {
            $this->error = mysqli_error($this->conn);
            return false;
        }
        
        $this->error = '';
        return true;
    }

    /**
     * Retrieves records from the database
     */
    public function &query(string $query): ?object
    {
        $null = null;

		// First check if the object is connected to the database
        if (!$this->is_connected()) {
			$this->error = 'Not connected to the database';
            return $null;
        }

		// Store the query for future refrences
		$this->query = $query;

        // Retrieve the records from the database
        $result = mysqli_query($this->conn, $query);

		// If query execution failed, store the error encountered
        if (!$result) {
            $this->error = mysqli_error($this->conn);
            return $null;
        }

        // Create a result object for the result handler if any records were returned
		if (is_object($result)) {
			$this->result = new MySqlResult($result);
		}

        $this->error = '';
        return $this->result;
    }
    
    /**
     * When the object is no longer needed, free the resources associated with it
     */
    function __destruct()
    {
        if (!empty($this->result)) {
            unset($this->result);
        }

        if (!empty($this->conn)) {
           mysqli_close($this->conn);
        }
    }
}

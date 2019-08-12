<?php
namespace Cradle\Components;

/**
 * Helps write to and get input from the command line
 */
class Console
{
    // The streams to work with
	private $streamIn;
	private $streamOut;

	public function __construct()
	{
		$this->streamIn = fopen('php://stdin', 'r');
		$this->streamOut = fopen('php://stdout', 'w');
	}

    /**
     * Prints to standard output
	 * 
	 * @param mixed Data to be printed to the console
	 * 
	 * @return null
     */
	public function print($output): void
	{
		fwrite($this->streamOut, sprintf(">>>%s\n", $output));
	}

    /**
     * Reads a line from standard output
	 * 
	 * @return string
     */
	public function read(): string
	{
		return trim(fgets($this->streamIn));
	}

	public function __destruct()
	{
		fclose($this->streamIn);
		fclose($this->streamOut);
	}
}

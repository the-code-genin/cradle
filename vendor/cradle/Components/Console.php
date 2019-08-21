<?php
namespace Cradle\Components;

/**
 * Provides useful methods to write to and get input from the command line.
 */
class Console
{
    /** @var resource $streamIn The input stream */
	private $streamIn;

	/** @var resource $streamOut The output stream */
	private $streamOut;

	public function __construct()
	{
		$this->streamIn = fopen('php://stdin', 'r');
		$this->streamOut = fopen('php://stdout', 'w');
	}

    /**
     * Prints data to standard output stream
	 * 
	 * @param mixed $output Data to be printed to the console
	 * 
	 * @return null
     */
	public function print($output): void
	{
		fwrite($this->streamOut, sprintf("%s\n", $output));
	}

    /**
     * Reads a line from the standard input stream
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

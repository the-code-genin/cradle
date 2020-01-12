<?php
namespace Cradle\Components\Exceptions;

/**
 * Extends the standard error class to handle uncaught errors
 */
class UncaughtError extends \Error
{
	public function __construct(string $message, string $file, int $line)
	{
		parent::__construct('', 0, null);
		$this->message = $message;
		$this->file = $file;
		$this->line = $line;
	}
}
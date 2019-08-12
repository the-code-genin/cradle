<?php
namespace Cradle\Components;

use Cradle\Components\Exceptions\UncaughtError;

/**
 * This class logs errors and exceptions to stdout.
 */
class Logger
{
	// Determines if errors should be logged to stdout
	public static $showErrors = true;

	/**
	 * Gets the type of throwable object.
	 * 
	 * @param \Throwable $e The throwable object
	 * 
	 * @return string The type of throwable
	 */
	protected static function getThrowableType(\Throwable $e): string
	{
		if (empty(get_parent_class($e))) {
			$type = ucfirst(get_class($e));
		} else {
			$type = is_subclass_of($e, '\Exception') ? 'Exception' : 'Error';
		}
		return $type;
	}

	/**
	 * Custom error handler for uncaught errors
	 * 
	 * @param \Throwable $e The throwable object
	 * 
	 * @return null
	 */
	public static function handleError(int $errno, string $errstr, string $errfile, int $errline): void
	{
		Logger::logThrowable(new UncaughtError($errstr, $errfile, $errline));
		http_response_code(503);
		exit(1);
	}

	/**
	 * Logs a throwable object to stdout.
	 * 
	 * @param \Throwable $e The throwable object
	 * 
	 * @return null
	 */
	public static function logThrowable(\Throwable $e): void
	{
		// Get the throwable's parameters
		$params = [
			'throwableType' => ucfirst(Logger::getThrowableType($e)),
			'throwableClass' => get_class($e),
			'throwableCode' => $e->getCode(),
			'throwableMessage' => $e->getMessage(),
			'throwableFile' => $e->getFile(),
			'throwableLine' => $e->getLine(),
			'throwableTrace' => $e->getTrace(),
		];

		// Generate the output and send to stdout if error reporting is allowed
		if (Logger::$showErrors) {
			$viewCompiler = new ViewCompiler();
			$viewCompiler->addView(new View('framework/error_exception', $params));
			echo $viewCompiler->compileViews();
		}
	}
}

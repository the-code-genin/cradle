<?php
namespace Cradle\Framework\Components;

/**
 * This class logs errors and exceptions to stdout.
 */
class Logger
{
	/**
	 * Gets the type of throwable object.
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
	 * Logs a throwable object to stdout.
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

		// Generate the output
		$viewCompiler = new ViewCompiler();
		$viewCompiler->addView(new View('framework/error_exception', $params));
		echo $viewCompiler->compileViews();
	}
}

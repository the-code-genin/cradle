<?php
namespace Cradle\Components\Exceptions;

/**
 * This exception is thrown when there is an error while compiling a view.
 */
class CompileException extends \Exception
{
	public function __construct($filePath, $code = 0, \Exception $previous = null) {
        parent::__construct("View file with file path '$filePath' was not found!", $code, $previous);
    }
}

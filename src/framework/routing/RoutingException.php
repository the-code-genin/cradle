<?php
namespace Cradle\Framework\Routing;

/**
 * This exception is thrown when there is an error while routing the URI request to the applicable controller.
 */
class RoutingException extends \Exception
{
	public function __construct($message = '', $code = 0, \Exception $previous = null) {
        parent::__construct('Invalid routing rule', $code, $previous);
    }
}

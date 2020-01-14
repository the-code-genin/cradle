<?php

/**
 * ------------------------------------------------------------------
 * Cradle
 * ------------------------------------------------------------------
 *
 * Cradle is an MVC microframework for building web apps with PHP.
 *
 * It is made with the aim to help php developers avoid working with spaghetti code
 * and embrace the MVC software architecture in as little time as possible.
 * It is totally free to use and open source.
 *
 * @package Cradle
 * @version 1.0
 * @author Mohammed Adekunle (Iyiola) <adekunle3317@gmail.com>
 */


/**
 * ------------------------------------------------------------------
 * ROUTING
 * ------------------------------------------------------------------
 *
 * The URI is matched with a routing rule.
 * If no valid rule is found the 404 error route is served.
 * In maintenance mode the maiantenance route is served.
 */

try {
	// Get the route rule to be used to handle the request
	if (getenv('APP_ENVIRONMENT') != 'maintenance') {
		$rule = Cradle\Routing\Router::getRouteRule();
	} else {
		$rule = App\Config\ROUTES['maintenance'];
	}

	// Dispatch the route rule to handle the request
	$dispatcher = new Cradle\Routing\Dispatcher();
	$dispatcher->dispatch($rule);
} catch (Throwable $e) {
	// Display the throwable if it is allowed
	\Cradle\Components\Logger::logThrowable($e);
	ob_end_flush();
	http_response_code(503);
	exit(1);
}




/**
 * ------------------------------------------------------------------
 * OUTPUT
 * ------------------------------------------------------------------
 *
 * Response is sent back to the user.
 * Output buffering is ended.
 */

echo $dispatcher->getResult(); // Send the final output to the client
ob_end_flush();

exit(0); // Exit successfully

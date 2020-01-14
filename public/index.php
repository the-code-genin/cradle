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
 * ERROR HANDLING
 * ------------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default 'development' will show all errors and exceptions
 * but 'production' and 'maintenance' will hide them.
 * If you define a custom environment setting you should make sure to create a case for it.
 */

$showThrowables = true; // Determines if exceptions should be logged

switch (getenv('APP_ENVIRONMENT')) { // Configure the exceptions and error logging levels based on the environment configuration

	case 'development': // All errors and exceptions are reported in development mode
		error_reporting(E_ALL);
		ini_set('display_errors', 'stdout');
		$showThrowables = true;
		break;

	case 'maintenance': // Site maintenance mode
		// Fall through

	case 'production': // No errors and exceptions are reported in production mode
		error_reporting(0);
		ini_set('display_errors', 'stderr');
		$showThrowables = false;
		break;

	default: // In case the environment was incorrectly set
		http_response_code(503);
		echo '<b>Error:</b> The application environment is not set correctly.';
		exit(1);
}

// Define a custom error handler for uncaught errors
// You errors shall not pass ;)
Cradle\Components\Logger::$showErrors = $showThrowables;
set_error_handler('Cradle\Components\Logger::handleError');




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
	if ($showThrowables) {
		Cradle\Components\Logger::logThrowable($e);
	}
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

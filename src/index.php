<?php
/**
 * ------------------------------------------------------------------
 * Cradle
 * ------------------------------------------------------------------
 *
 * A light weight framework built with the aim to avoid working with monolithic web apps
 * while still giving the developer as much freedom as possible to work in an MVC environment.
 *
 * @package Cradle
 * @version 1.0
 * @author Mohammed Adekunle (Iyiola) <adekunle3317@gmail.com>
 */

// Get the time cradle is fired up.
define('CRADLE_START', time());

// The working environment.
// Valid options are 'development', 'production' and 'maintenance'.
// If you can define a custom environment you should ensure you create a case for it in error handling.
define('CRADLE_ENVIRONMENT', 'development');

// Ensure the server is running the minimum PHP version.
// Current minimum: 7.2
if (version_compare(PHP_VERSION, '7.2', '<')) {
	http_response_code(503);
	echo '<b>Error:</b> Cradle requires a minimum PHP version of 7.2 to run. Current PHP version: ' . PHP_VERSION;
	exit(1);
}




/**
 * ------------------------------------------------------------------
 * BOOTSTRAPPING
 * ------------------------------------------------------------------
 *
 * The autoloaders are included.
 * Site constants are loaded.
 * The session is started and output buffering is initiated.
 */

ob_start();
session_start([
	'cookie_httponly' => true,
	'cookie_secure' => isset($_SERVER['HTTPS']),
	'use_strict_mode' => true,
]);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/cradle-autoloader.php';
require_once __DIR__ . '/application/config/autoload.php';




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

// Determines if exceptions should be logged
$showThrowables = true;

// Configure the exceptions and error logging levels based on the environment configuration
switch (CRADLE_ENVIRONMENT) {
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

// Define a custom error handler for uncaught errors, you shall not pass ;)
Cradle\Framework\Components\Logger::$showErrors = $showThrowables;
set_error_handler('Cradle\Framework\Components\Logger::handleError');




/**
 * ------------------------------------------------------------------
 * ROUTING
 * ------------------------------------------------------------------
 *
 * The URI is matched with a routing rule.
 * If no valid rule is found the 404 error route is served.
 * In maintenance mode the maintenance route is served.
 */

try {
	// Get the route rule to be used
	if (CRADLE_ENVIRONMENT != 'maintenance') {
		$rule = Cradle\Framework\Routing\Router::getRouteRule();
	} else {
		$rule = Cradle\Application\Config\ROUTES['maintenance'];
	}

	// Dispatch the route rule
	$dispatcher = new Cradle\Framework\Routing\Dispatcher();
	$dispatcher->dispatch($rule);
} catch (Throwable $e) {
	// Display the throwable if it is allowed
	if ($showThrowables) {
		Cradle\Framework\Components\Logger::logThrowable($e);
	}
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

// Send the final output to the client
$output = $dispatcher->getResult();
echo $output;
ob_end_flush();

// Exit successfully
exit(0);

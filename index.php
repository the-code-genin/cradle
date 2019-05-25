<?php
/**
 * ------------------------------------------------------------------
 * Cradle
 * ------------------------------------------------------------------
 *
 * A lightweight and extensible framework with the aim to avoid working with monolithic web apps.
 * Cradle is aimed at being easily extensible and as such only ships with the basic functions of autoloading,
 * error handling, URI routing and dynamic page generation.
 *
 * @package Cradle
 * @version 1.0
 * @author Mohammed Adekunle <adekunle3317@gmail.com>
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
	echo '<b>Fatal Error:</b> Cradle requires a minimum PHP version of 7.2 to run. Current PHP version: ' . PHP_VERSION;
	exit(1);
}



/*
| ------------------------------------------------------------------
| BOOTSTRAPPING
| ------------------------------------------------------------------
|
| The autoloader functions are included.
| Site constants are loaded.
| The session is started and buffering is initiated.
*/

ob_start();
session_start();
require_once __DIR__ . '/vendors/autoload.php';
require_once __DIR__ . '/application/config/autoload.php';



/*
| ------------------------------------------------------------------
| ERROR HANDLING
| ------------------------------------------------------------------
|
| Different environments will require different levels of error reporting.
| By default 'development' will show all errors but 'production' and 'maintenance' will hide them.
| If you define a custom environment setting you should make sure to create a case for it.
*/

switch (CRADLE_ENVIRONMENT) {
	case 'development': // All errors are reported in development mode
		error_reporting(E_ALL);
		ini_set('display_errors', 'stdout');
	break;

	case 'maintenance': // Site maintenance mode
		// Fall through

	case 'production': // No errors are reported in production mode
		error_reporting(0);
		ini_set('display_errors', 'stderr');
	break;

	default: // Incase the environment was incorrectly set
		http_response_code(503);
		echo '<b>Fatal Error:</b> The application environment is not set correctly.';
		exit(1);
}



/**
| ------------------------------------------------------------------
| ROUTING
| ------------------------------------------------------------------
|
| The URI is matched with a routing rule.
| If no valid rule is found the 404 error route is served.
| In maintenance mode the maintenance route is served.
*/

// Get the rule to be used
if (CRADLE_ENVIRONMENT != 'maintenance') {
	$rule = Cradle\Framework\Router::getRouteRule();
} else {
	$rule = Cradle\Application\Config\ROUTES['maintenance'];
}

// Resolve the rule into a route
try {
	$route = Cradle\Framework\Router::parseRouteRule($rule);
} catch (Exception $e) {
	echo "<b>Error:</b> {$e->getMessage()}";
	http_response_code(503);
	exit(1);
}

// Construct the eval string to point to the requested controller and the corresponding method
$controllerClass = 'Cradle\\Application\\Controllers\\' . $route['controller'];
$controller = new $controllerClass(CRADLE_START);

$controllerMethod = $route['method'] . '(';
if (count($route['parameters'])) {
	$controllerMethod .= implode(',', $route['parameters']);
}
$controllerMethod .= ');';

// Instantiate the requested controller and method
try {
	eval('$controller->' . "$controllerMethod");
} catch (Exception $e) {
	echo "<b>Error:</b> {$e->getMessage()}";
	http_response_code(503);
	exit(1);
}



/**
| ------------------------------------------------------------------
| OUTPUT
| ------------------------------------------------------------------
|
| Response is sent back to the user.
| Output buffering is ended.
*/

// Send the final output to the client
$output = $controller->getOutput();
echo $output;
ob_end_flush();

// Exit successfully.
exit(0);

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
 * @author Mohammed Adekunle <adekunle3317@gmail.com>
 */

// Define the working environment.
// Valid options are 'development', 'production' and 'maintenance'.
define('CRADLE_ENVIRONMENT', 'development');



/*
| ------------------------------------------------------------------
| BOOTSTRAPPING
| ------------------------------------------------------------------
|
| 
*/

ob_start();
session_start();
require_once __DIR__ . '/vendors/autoload.php';
require_once __DIR__ . '/application/config/autoload.php';

// Require all the site configuration files
foreach ($configFiles as $file) {
	require_once __DIR__ . '/application/config/' . $file . '.php';
}



/*
| ------------------------------------------------------------------
| ERROR HANDLING
| ------------------------------------------------------------------
|
| Different environments will require different levels of error reporting.
| By default 'development' will show all errors but 'production' will hide them.
| 'maintenance' will show a generic maintenance page
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
|
*/

// If the site if running under the normal conditions
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
$controller = new $controllerClass;

$controllerMethod = $route['method'] . '(';
if (count($route['parameters'])) {
	$controllerMethod .= implode(',', $route['parameters']);
}
$controllerMethod .= ');';

// Instantiate the requested controller
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
| 
*/

// Send the final output to the client
$output = $controller->getOutput();
echo $output;
ob_end_flush();

// Exit successfully
exit(0);

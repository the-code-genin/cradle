<?php
namespace Cradle\Core;

use const Cradle\Application\CONFIG;

/**
 * The Router class contains static methods for mapping request URI to a defined route.
 */
class Router
{
	/**
	 * Auto-completes a route pattern by replacing the (:any) and (:num) rules with the correct regex matching options.
	 */
	private static function completeRoutePattern(string $pattern): string
	{
		$validChars = CONFIG['routing']['allowed_uri_characters'];
		$pattern = preg_replace('#\(:num\)#i', '([0-9]*)', $pattern);
		$pattern = preg_replace('#\(:any\)#i', "([$validChars]*)", $pattern);
		return $pattern;
	}

	/**
	 * Returns the autocompleted route pattern for the current request URI.
	 */
	private static function getRoutePattern(): ?string
	{
		// Get the routing rules
		$routes = CONFIG['routing']['routes'];

		// Get a route rule for the request URI by matching it with the defined routing rules' patterns
		$requestURI = $_SERVER['REQUEST_URI'];
		foreach ($routes as $pattern => $rule) {
			$re = '#^' . Router::completeRoutePattern($pattern) . '/?$#i';
			if (preg_match($re, $requestURI)) {
				return Router::completeRoutePattern($pattern);
			}
		}

		// If no route rule is found
		return null;
	}

	/**
	 * Returns the routing rule applicable to the current request URI.
	 */
	public static function getRouteRule(): string
	{
		// Get the routing rules
		$routes = CONFIG['routing']['routes'];

		// Get a route rule for the request URI by matching it with the defined routing rules' patterns
		$requestURI = $_SERVER['REQUEST_URI'];
		foreach ($routes as $pattern => $rule) {
			$pattern = '#^' . Router::completeRoutePattern($pattern) . '/?$#i';
			if (preg_match($pattern, $requestURI)) {
				return $rule;
			}
		}

		// If no valid route rule is found, return the 404 error route rule
		return CONFIG['routing']['404_error'];
	}

	/**
	 * Parses a route rule and gets the controller, method and parameters to be used.
	 */
	public static function parseRouteRule(string $rule): ?array
	{
		// Explode the rule
		$rule = explode('/', $rule);
		for ($i = 0; $i < count($rule); $i++) {
			if (empty($rule[$i])) {
				array_splice($rule, $i, 1);
			}
		}

		// If an invalid route rule was supplied
		if (count($rule) < 2) {
			throw new \Exception('Invalid routing rule');
			return null;
		}

		// Get the controller and method to be used
		$route = [
			'controller' => $rule[0],
			'method' => $rule[1],
			'parameters' => []
		];

		// Get any parameters if they exist
		if (count($rule) > 2) {
			// Get the order of parameters
			$parameters = array_slice($rule, 2);
			
			// Match the request URI with the route pattern
			$pattern = '#^' . Router::getRoutePattern() . '/?$#i';
			preg_match_all($pattern, $_SERVER['REQUEST_URI'], $matches);

			// Insert the matched parameters in the right position
			for ($i = 1; $i < count($matches); $i++) {
				$parameter = $matches[$i][0];
				for ($j = 0; $j < count($parameters); $j++) {
					if ($parameters[$j] == "\$$i") {
						$parameters[$j] = "'$parameter'";
					}					
				}
			}

			$route['parameters'] = $parameters;
		}

		return $route;
	}
}

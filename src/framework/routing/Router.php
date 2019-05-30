<?php
namespace Cradle\Framework\Routing;

use const Cradle\Application\Config\ROUTES;

/**
 * The Router class contains static methods for mapping a request URI to a defined route.
 */
class Router
{
	/**
	 * Returns the routing rule applicable to the current request URI.
	 */
	public static function getRouteRule(): string
	{
		// Get a route rule for the request URI by matching it with the defined routing rules patterns
		$routePattern = Router::getRoutePattern();
		if ($routePattern != null) {
			$rule = ROUTES['routes'][$routePattern];
			return $rule;
		}

		// If no valid route rule is found
		return ROUTES['404_error'];
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
			throw new RoutingException;
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
			$pattern = Router::getRoutePattern();
			if ($pattern == null) {
				throw new RoutingException;
			}
			$pattern = '#^' . Router::replaceShorthand($pattern) . '/?$#i';
			preg_match_all($pattern, $_SERVER['REQUEST_URI'], $matches);

			// Insert the matched parameters in the right position
			for ($i = 1; $i < count($matches); $i++) {
				$parameter = $matches[$i][0];
				for ($j = 0; $j < count($parameters); $j++) {
					if ($parameters[$j] == "\$$i") {
						$parameters[$j] = $parameter;
					}
				}
			}

			$route['parameters'] = $parameters;
		}

		return $route;
	}

	/**
	 * Returns the applicable route pattern for the current request URI.
	 */
	protected static function getRoutePattern(): ?string
	{
		// Get a route rule for the request URI by matching it with the defined routing rules' patterns
		$requestURI = $_SERVER['REQUEST_URI'];
		foreach (ROUTES['routes'] as $pattern => $rule) {
			$pat = '#^' . Router::replaceShorthand($pattern) . '/?$#i';
			if (preg_match($pat, $requestURI)) {
				return $pattern;
			}
		}

		// If no route rule is found
		return null;
	}

	/**
	 * Auto-completes a route pattern by replacing the route shorthands with the correct regex.
	 */
	protected static function replaceShorthand(string $pattern): string
	{
		foreach (ROUTES['shorthand'] as $shorthand => $regex) {
			$pattern = preg_replace("#<%$shorthand%>#i", "($regex)", $pattern);
		}
		return $pattern;
	}
}

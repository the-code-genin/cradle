<?php
namespace Cradle\Routing;

use Cradle\Routing\Exceptions\RoutingException;
use const App\Config\ROUTES;

/**
 * The Router class contains static methods for mapping a request URI to a defined route.
 */
class Router
{
	/**
	 * Returns the routing rule applicable to the current request URI.
	 *
	 * @return string The routing rule
	 */
	public static function getRouteRule(): string
	{
		// Get a route rule for the request URI by matching it with the defined routing rules patterns
		$routePattern = self::getRoutePattern();
		if ($routePattern != null) {
			$rule = ROUTES['routes'][$routePattern];
			return $rule;
		}

		// If no valid route rule is found
		return ROUTES['404_error'];
	}

	/**
	 * Parses a route rule and gets the controller class, controller method and controller method parameters to be used to handle
	 * the current request.
	 *
	 * @param string $rule The routing rule to parse
	 * @return array|null
	 */
	public static function parseRouteRule(string $rule): ?array
	{
		// Split the rule into the controller namespace and controller method
		$rule = explode('@', $rule);
		if (count($rule) != 2) {
			throw new RoutingException;
		}

		// Parse the controller method section of the rule
		$rule[1] = explode('/', $rule[1]);
		for ($i = 0; $i < count($rule[1]); $i++) {
			if (empty($rule[1][$i])) {
				array_splice($rule[1], $i, 1);
			}
		}

		// Check if an invalid route rule was supplied
		if (!class_exists('App\\Controllers\\' . $rule[0]) | count($rule[1]) < 1) {
			throw new RoutingException;
		}

		// Get the controller class and controller method to be used
		$route = [
			'controller' => $rule[0],
			'method' => $rule[1][0],
			'parameters' => []
		];

		// Get any controller method parameters if they exist
		if (count($rule[1]) > 1) {
			$parameters = array_slice($rule[1], 1); // Get the order of parameters
			
			$pattern = self::getRoutePattern();
			if ($pattern == null) { // Match the request URI with the route pattern
				throw new RoutingException;
			}

			$pattern = '#^' . self::replaceShorthand($pattern) . '/?$#i';
			preg_match_all($pattern, self::getURIWithoutQueryString(), $matches);

			// Insert the matched parameters in the right position
			for ($i = 1; $i < count($matches); $i++) {
				$parameter = $matches[$i][0];
				for ($j = 0; $j < count($parameters); $j++) {
					if ($parameters[$j] == "\$$i") {
						$parameters[$j] = $parameter;
					}
				}
			}

			// Assign the route parameters
			$route['parameters'] = $parameters;
		}

		return $route;
	}

	/**
	 * Returns the applicable route pattern for the current request URI.
	 *
	 * @return string|null
	 */
	protected static function getRoutePattern(): ?string
	{
		$requestURI = self::getURIWithoutQueryString();
		foreach (array_keys(ROUTES['routes']) as $pattern) {
			$pat = '#^' . self::replaceShorthand($pattern) . '/?$#i';
			if (preg_match($pat, $requestURI)) {
				return $pattern;
			}
		}

		return null; // If no applicable route rule is found
	}

	/**
	 * Auto-completes a route pattern by replacing the route shorthands with the correct regex as specified in the routes config file.
	 *
	 * @param string $pattern The pattern to autocomplete
	 * @return string The autocompleted pattern
	 */
	protected static function replaceShorthand(string $pattern): string
	{
		foreach (ROUTES['shorthand'] as $shorthand => $regex) {
			$pattern = preg_replace("#\{$shorthand\}#", "($regex)", $pattern);
		}
		return $pattern;
	}

	/**
	 * Gets the request URI without the query string
	 * 
	 * @return string The request URI without the query string
	 */
	protected static function getURIWithoutQueryString(): string
	{
		$requestURI = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
		return $requestURI;
	}
}

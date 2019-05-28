<?php
namespace Cradle\Framework\Routing;

/**
 * This class is responsible for instantiating controllers and getting their operation output.
 */
class Dispatcher
{
	// Stores the output of the recently dispatched controller
	private $result = '';

	/**
	 * Get the output of the recently dispatched controller.
	 */
	public function getResult()
	{
		return $this->result;
	}

	/**
	 * Serve a controller and store the output of the operation.
	 */
	public function dispatch(string $rule): void
	{
		// Resolve the rule into a route
		$route = Router::parseRouteRule($rule);

		$controllerClass = 'Cradle\\Application\\Controllers\\' . $route['controller'];
		$controller = new $controllerClass(CRADLE_START);
		$method = $route['method'];
		$parameters = $route['parameters'];

		// Instantiate the requested controller and method with parameters
		call_user_func_array([$controller, $method], $parameters);

		// Get the output of the operation
		$this->result = $controller->getOutput();
	}
}

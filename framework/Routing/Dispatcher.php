<?php
namespace Cradle\Routing;

/**
 * This class is responsible for instantiating controllers and getting the output of their operations.
 */
class Dispatcher
{
	/** @var string $result The output of the recently dispatched controller*/
	private $result = '';

	/**
	 * Get the output of the recently dispatched controller.
	 * 
	 * @return mixed The result of output of the recently dispatched controller
	 */
	public function getResult()
	{
		return $this->result;
	}

	/**
	 * Serve a controller and store the output of the operation.
	 * 
	 * @param string $rule The routing rule to be dispatched
	 * 
	 * @return null
	 */
	public function dispatch(string $rule): void
	{
		// Resolve the rule into a route
		$route = Router::parseRouteRule($rule);

		$controllerClass = 'App\\Controllers\\' . $route['controller']; // Get the controller class

		$controller = new $controllerClass();
		$method = $route['method'];
		$parameters = $route['parameters'];

		call_user_func_array([$controller, $method], $parameters); // Instantiate the requested controller and method with parameters

		$this->result = $controller->getOutput(); // Get and store the output of the operation
	}
}

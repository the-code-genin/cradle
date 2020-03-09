<?php
namespace Cradle;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Database\Capsule\Manager;
use Slim\Exception\HttpInternalServerErrorException;

/**
 * The base class for all controllers in the system.
 * All valid controllers must either extend this class or extend one of its subclasses.
 */
abstract class Controller
{
	/** @var Container $container A container instance. */
	protected $container;

	/** @var ResponseInterface $response The current response being handled by the controller. */
	protected $response;


	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * Parse the response from a controller
	 *
	 * @param ResponseInterface $response
	 * @param mixed $body
	 * @return ResponseInterface
	 */
	protected function parseResponseBody(ResponseInterface $response, $body): ResponseInterface
	{
		switch (gettype($body)) {
			case 'string': // Plain text
				$response->getBody()->write($body);
				$contentType = 'text/plain';
			break;

			case 'array': // An array
				$response->getBody()->write(json_encode($body));
				$contentType = 'application/json';
			break;

			case 'object': // If a view file is returned
				if (get_class($body) != View::class & !is_subclass_of($body, View::class)) {
					break;
				}

				$viewCompiler = $this->container->get('view');
				$viewCompiler->clearViews();
				$viewCompiler->addView($body);
				$response->getBody()->write($viewCompiler->compileViews());
				$contentType = 'text/html';
			break;

			default: // If now response from the controller
				$contentType = null;
			break;
		}

		if (!is_null($contentType) && count($response->getHeader('Content-Type')) == 0) { // If a content type was not set and one was detected.
			$response = $response->withHeader('Content-Type', $contentType);
		}

		return $response;
	}

	/**
	 * Set a header
	 *
	 * @param string $name
	 * @param string $value
	 * @return array The array of header values for that header
	 */
	protected function setHeader(string $name, string $value): array
	{
		$this->response = $this->response->withHeader($name, $value);
		return $this->getHeader($name);
	}

	/**
	 * Get an array of header values for a particular header
	 *
	 * @param string $name
	 * @return array
	 */
	protected function getHeader(string $name): array
	{
		return $this->response->getHeader($name);
	}

	/**
	 * When a controller method is called
	 *
	 * @param string $name
	 * @param array $arguments
	 * @return void
	 */
	public function __call(string $name, array $arguments): ResponseInterface
	{
		if (!method_exists($this, $name)) { // Method doesn't exist
			throw new HttpInternalServerErrorException($arguments[0]);
		}

		$request = $arguments[0];
		$params = (object) $arguments[2];
		$this->response = $arguments[1];

		$body = call_user_func_array([$this, $name], [$request, $params]);
		$this->response = $this->parseResponseBody($this->response, $body);
		
		return $this->response;
	}
}

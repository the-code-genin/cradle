<?php
namespace Cradle;

use Throwable;
use Cradle\ViewCompiler;
use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpSpecializedException;
use Slim\Exception\HttpInternalServerErrorException;

/**
 * This class logs errors and exceptions.
 */
class Logger
{
	/** @var ViewCompiler $viewCompiler The view compiler instance. */
	protected $viewCompiler;

    public function __construct()
    {
        $this->viewCompiler = new ViewCompiler();
	}

	/**
	 * Gets the type of throwable object.
	 * 
	 * @param \Throwable $e The throwable object
	 * @return string The type of throwable
	 */
	protected function getThrowableType(\Throwable $e): string
	{
		if (empty(get_parent_class($e))) {
			$type = ucfirst(get_class($e));
		} else {
			$type = is_subclass_of($e, '\Exception') ? 'Exception' : 'Error';
		}

		return $type;
	}

	/**
	 * Parse a stack trace.
	 *
	 * @param array $trace
	 * @return array
	 */
	protected function parseTrace(array $trace): array
	{
		$parsedTrace = [];

		foreach ($trace as $subTrace) {
			$parsedArgs = [];

			foreach ($subTrace['args'] as $arg) {
				$parsedArg = ucfirst(gettype($arg)) . ' ';

				switch (gettype($arg)) {
					case 'array':
						$parsedArg .= sprintf('(%s)', count($arg));
						break;
					case 'resource':
						break;
					case 'object':
						$parsedArg .= get_class($arg);
						break;
					default:
						$parsedArg .= sprintf('"%s"', (string) $arg);
					break;
				}

				array_push($parsedArgs, $parsedArg);
			}

			$subTrace['args'] = $parsedArgs;
			array_push($parsedTrace, $subTrace);
		}

		return $parsedTrace;
	}

	/**
	 * Handle an exception or error.
	 *
	 * @param ServerRequestInterface $request
	 * @param Throwable $exception
	 * @param boolean $displayErrorDetails
	 * @param boolean $logErrors
	 * @param boolean $logErrorDetails
	 * @return ResponseInterface
	 */
	public function __invoke (
		ServerRequestInterface $request,
		Throwable $e,
		bool $displayErrorDetails,
		bool $logErrors,
		bool $logErrorDetails
	): ResponseInterface {
		$params = [
			'throwableType' => ucfirst($this->getThrowableType($e)),
			'throwableClass' => get_class($e),
			'throwableCode' => $e->getCode(),
			'throwableMessage' => $e->getMessage(),
			'throwableFile' => $e->getFile(),
			'throwableLine' => $e->getLine(),
			'throwableTrace' => $this->parseTrace($e->getTrace()),
		];

		$viewCompiler = $this->viewCompiler;

		if (is_subclass_of($e, HttpSpecializedException::class)) { // Routing error
			$view = new View('framework/error_http.twig', ['exception' => $e]);
		} else if ($displayErrorDetails == true) { // If errors are to be displayed
			$view = new View('framework/error_exception.twig', $params);
		} else { // If in errors are not to be displayed
			$exception = new HttpInternalServerErrorException($request);
			$view = new View('framework/error_http.twig', ['exception' => $exception]);
		}

		$viewCompiler->addView($view);

		// Get the response
		$responseFactory = new ResponseFactory();
		$response = $responseFactory->createResponse()->withHeader('Content-Type', 'text/html');
		$response->getBody()->write($viewCompiler->compileViews());

		return $response;
	}
}

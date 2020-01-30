<?php
namespace Cradle;

use Slim\App;
use Throwable;
use Cradle\ViewCompiler;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpSpecializedException;

/**
 * This class logs errors and exceptions.
 */
class Logger
{
	/** @var App $app The slim app instance. */
	protected $app;
	
	/**
     * @param App $app The slim app instance to be used by the kernel.
     */
    public function __construct(App $app)
    {
        $this->app = $app;
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
	 * Parse a stack trace
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
						$parsedArg .= (string) $arg;
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
	 * @return void
	 */
	public function __invoke (
		ServerRequestInterface $request,
		Throwable $e,
		bool $displayErrorDetails,
		bool $logErrors,
		bool $logErrorDetails
	) {
		$params = [
			'throwableType' => ucfirst(Logger::getThrowableType($e)),
			'throwableClass' => get_class($e),
			'throwableCode' => $e->getCode(),
			'throwableMessage' => $e->getMessage(),
			'throwableFile' => $e->getFile(),
			'throwableLine' => $e->getLine(),
			'throwableTrace' => $this->parseTrace($e->getTrace()),
		];

		/** @var ViewCompiler $viewCompiler A view compiler. */
		$viewCompiler = $this->app->getContainer()->get('view');

		if (is_subclass_of($e, HttpSpecializedException::class)) { // Routing error
			$viewCompiler->addView(new View('framework/error_http.twig', ['exception' => $e]));
		} else if ($displayErrorDetails == true) { // If errors are to be displayed
			$viewCompiler->addView(new View('framework/error_exception.twig', $params));
		}

		// Get the response
		$response = $this->app->getResponseFactory()->createResponse();
		$response->getBody()->write($viewCompiler->compileViews());

		return $response;
	}
}

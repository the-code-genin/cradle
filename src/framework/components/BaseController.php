<?php
namespace Cradle\Framework\Components;

use Cradle\Framework\Managers\{AssetManager, CookieManager, RequestManager, SessionManager};
use Cradle\Framework\Routing\Dispatcher;
use const Cradle\Application\Config\ROUTES;

/**
 * The base class for all controllers in the system.
 * All valid controllers must either extend this class or extend one of its subclasses.
 */
abstract class BaseController
{
	// The resource managers for the controller
	protected $assetManager;
	protected $cookieManager;
	protected $sessionManager;
	protected $requestManager;

	// Holds the ViewCompiler object for the controller
	protected $viewCompiler;

	// Alternate output to be sent back to the client
	protected $outputOverridden = false;
	protected $output = '';

	// The time this cradle app was fired up
	protected $startTime;

	public function __construct(int $startTime)
	{
		$this->viewCompiler = new ViewCompiler();
		$this->assetManager = new AssetManager();
		$this->cookieManager = new CookieManager();
		$this->sessionManager = new SessionManager();
		$this->requestManager = new RequestManager();
		$this->startTime = $startTime;
	}

	/**
	 * Loads a view file into the ViewCompiler.
	 */
	protected function loadView(string $filePath, array $param = []): void
	{
		$view = new View($filePath, $param);
		$this->viewCompiler->addView($view);
	}

	/**
	 * Gets the output to be sent as response to the client.
	 */
	public function getOutput()
	{
		// If a custom output has already been specified
		if ($this->outputOverridden) {
			return $this->output;
		}

		// If not compile the view objects supplied
		return $this->viewCompiler->compileViews();
	}

	/**
	 * Manually sets the response to be sent back to the client.
	 */
	protected function setOutput($output): bool
	{
		if (!$this->outputOverridden) {
			$this->outputOverridden = true;
			$this->output = $output;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Manually sets the asset file to be sent back to the client.
	 * It automatically detects the file MIME type and sets the Content-Type header.
	 */
	protected function setOutputFile(string $path): bool
	{
		if (!$this->outputOverridden & $this->assetManager->fileExists($path)) {
			$this->outputOverridden = true;
			$this->setHeader('Content-Type', $this->assetManager->fileMIME($path));
			$this->output = $this->assetManager->readFile($path);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Sets an header value to be sent back to the client.
	 */
	protected function setHeader(string $header, string $value): void
	{
		header("$header: $value");
	}

	/**
	 * Gets how many seconds this cradle app has been running for.
	 */
	public function getExecutionTime(): int
	{
		return time() - $this->startTime;
	}

	/**
	 * Gets the maximum execution time for this cradle app.
	 */
	public function getMaxExecutionTime(): int
	{
		return @ini_get('max_execution_time');
	}

	/**
	 * Adds to the max timeout limit for the application.
	 * NOTE: Only works in safe mode.
	 */
	public function addExecutionTime(int $secs): bool
	{
		if ($secs < 0) {
			throw new \Exception('Invalid timeout limit');
			return false;
		}

		// Compute the remaining execution time
		$remainingTime = $this->getMaxExecutionTime() - $this->getExecutionTime();

		if ($remainingTime >= 0) {
			return set_time_limit($remainingTime + $secs);
		}

		return set_time_limit($secs);
	}

	/**
	 * Show the 404 error page.
	 * Do not call this method from the 404 error controller!
	 */
	protected function show404(): void
	{
		$rule = ROUTES['404_error'];
		$dispatcher = new Dispatcher();
		$dispatcher->dispatch($rule);
		$this->output = $dispatcher->getResult();
		$this->outputOverridden = true;
	}
}

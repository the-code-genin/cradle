<?php
namespace Cradle\Framework;

use Cradle\Framework\{ViewCompiler, View, AssetManager};

/**
 * The base class for all controllers in the system.
 * All valid controllers must either extend this class or extend one of its subclasses.
 */

abstract class Controller
{
	// The AssetManager for the controller
	protected $assetManager;

	// Holds the ViewCompiler object for the controller
	protected $viewCompiler;

	// States if the output is overridden
	protected $outputOverridden = false;

	// Stores the overridden output to be sent back to the client
	protected $output = '';

	// The time this cradle app is fired up
	protected $startTime;

	public function __construct(int $startTime)
	{
		$this->viewCompiler = new ViewCompiler();
		$this->assetManager = new AssetManager();
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

		// If not compile the view objects compiled if any
		return $this->viewCompiler->compileViews();
	}

	/**
	 * Manually sets the response to be sent back to the client
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
	 * Manually sets the asset file to be sent back to the client
	 * It automatically detects the file MIME type and sets the Content-Type header
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
	 * Sets an header value to be sent back to the client
	 */
	protected function setHeader(string $header, string $value): void
	{
		header("$header: $value");
	}

	/**
	 * Returns a reference to the controller's AssetManager instance
	 */
	protected function &getAssetManager(): AssetManager
	{
		return $this->assetManager;
	}

	/**
	 * Gets how many seconds this cradle app has been running for
	 */
	public function getExecutionTime(): int
	{
		return time() - $this->startTime;
	}

	/**
	 * Gets the maximum execution time for this cradle app
	 */
	public function getMaxExecutionTime(): int
	{
		return @ini_get('max_execution_time');
	}

	/**
	 * Adds to the max timeout limit for the application
	 * It is useful if your application will do some long processing at this point
	 * NOTE: Only works in safe mode
	 */
	protected function addExecutionTime(int $secs): bool
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
}

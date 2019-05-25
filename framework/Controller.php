<?php
namespace Cradle\Framework;

use Cradle\Framework\{ViewCompiler, View, AssetManager};

/**
 * The abstract base class for all controllers in the system.
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

	public function __construct()
	{
		$this->viewCompiler = new ViewCompiler();
		$this->assetManager = new AssetManager();
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
	 * Sets the response to be sent back to the client
	 */
	protected function setOutput($output): void
	{
		$this->outputOverridden = true;
		$this->output = $output;
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
}

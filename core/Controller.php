<?php
namespace Cradle\Core;

use Cradle\Core\{ViewCompiler, View};
use const Cradle\Application\CONFIG;

/**
 * The abstract super class for all controllers in the system.
 */
abstract class Controller
{
	// Holds the ViewCompiler instance for the controller
	private $viewCompiler;

	// If the output is overridden
	private $outputOverridden = false;

	// Stores the overridden output
	private $output = '';

	/**
	 * Autoloads some objects into the controller
	 */
	public function __construct()
	{
		$this->viewCompiler = new ViewCompiler();
	}

	/**
	 * Loads a view file into the ViewCompiler
	 */
	protected final function loadView(string $filePath, array $param = []): void
	{
		$view = new View($filePath, $param);
		$this->viewCompiler->addView($view);
	}

	/**
	 * Gets the output to be sent as response to the client
	 */
	public final function getOutput(): string
	{
		if ($this->outputOverridden) {
			return $this->output;
		}

		return $this->viewCompiler->compileViews();
	}

	/**
	 * Sets the response string
	 */
	protected final function setOutput(string $output): void
	{
		$this->outputOverridden = true;
		$this->output = $output;
	}

	/**
	 * Sets an header value
	 */
	protected final function setHeader(string $header, string $value): void
	{
		header("$header: $value");
	}

	/**
	 * Gets the configuration object
	 */
	protected final function getConfig(): array
	{
		return CONFIG;
	}
}

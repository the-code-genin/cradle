<?php
namespace Cradle\Framework;

use Cradle\Framework\{ViewCompiler, View};

/**
 * The abstract base class for all controllers in the system.
 * All valid controllers must either extend this class or extend one of its subclasses.
 */
abstract class Controller
{
	// Holds the ViewCompiler object for the controller
	protected $viewCompiler;

	// States if the output is overridden
	protected $outputOverridden = false;

	// Stores the overridden output to be sent back to the client
	protected $output = '';

	public function __construct()
	{
		$this->viewCompiler = new ViewCompiler();
	}

	/**
	 * Loads a view file into the ViewCompiler.
	 */
	protected final function loadView(string $filePath, array $param = []): void
	{
		$view = new View($filePath, $param);
		$this->viewCompiler->addView($view);
	}

	/**
	 * Gets the output to be sent as response to the client.
	 */
	public final function getOutput()
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
	protected final function setOutput($output): void
	{
		$this->outputOverridden = true;
		$this->output = $output;
	}

	/**
	 * Sets an header value to be sent back to the client
	 */
	protected final function setHeader(string $header, string $value): void
	{
		header("$header: $value");
	}
}

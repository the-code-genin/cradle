<?php
namespace Cradle\Framework;

use Cradle\Framework\View;

/**
 * Acts as a compiler for the views that will eventually be sent to the client.
 */

class ViewCompiler
{
	// Stores the view objects in the order that they will be compiled
	protected $views = [];

	/**
	 * Adds a new view object to the array of view objects to be compiled later.
	 */
	public function addView(View $view): void
	{
		array_push($this->views, $view);
	}

	/**
	 * Compiles a view object for output.
	 */
	protected function compileView(View $view): ?string
	{
		// Check if the file to be compiled exists
		$filePath = $view->getFilePath();
		if (!file_exists($filePath)) {
			throw new \Exception("View file with file path '$file_path' was not found!");
			return null;
		}

		// Create the variables to be passed into the view file
		$viewParameters = $view->getParameters();
		foreach ($viewParameters as $parameter => $value) {
			$$parameter = $value;
		}

		// Compile the view file
		ob_start();
		require $filePath;
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	/**
	 * Compiles all the view objects in the order in which they were added.
	 * It returns the concatenated output.
	 */
	public function compileViews(): string
	{
		$output = '';
		foreach ($this->views as $view) {
			$output .= $this->compileView($view);
		}

		return $output;
	}
}

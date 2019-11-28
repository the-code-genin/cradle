<?php
namespace Cradle\Components;

use Cradle\Components\Exceptions\CompileException;

/**
 * A compiler for the views that will eventually be sent to the client.
 */
class ViewCompiler
{
	/** @var array $views Stores the view objects in the order that they will be compiled */
	protected $views = [];

	/**
	 * Adds a new view object to the array of view objects to be compiled later.
	 *
	 * @param View $view The view to be compiled
	 * @return null
	 */
	public function addView(View $view): void
	{
		array_push($this->views, $view);
	}

	/**
	 * Compiles a view object for output.
	 *
	 * @param View $view The view to be compiled
	 * @return string|null The output of the compiled view
	 */
	protected function compileView(View $view): ?string
	{
		// Check if the file to be compiled exists
		$filePath = $view->getFilePath();
		if (!file_exists($filePath)) {
			throw new CompileException($filePath);
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
	 *
	 * @return string The concatenated output of all the indivdual views
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

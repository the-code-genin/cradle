<?php
namespace Cradle;

use Twig\Environment;
use Cradle\View;
use Twig\Loader\FilesystemLoader;
use Cradle\Exceptions\CompileException;

/**
 * A compiler for the views that will eventually be sent to the client.
 */
class ViewCompiler
{
	/** @var array $views Stores the view objects in the order that they will be compiled. */
	protected $views = [];

	/** @var array $defaultParameters These parameters are passed into every compiled view file. */
	protected $defaultParameters = [];

	/**
	 * Adds a new view object to the array of view objects to be compiled later.
	 *
	 * @param View $view The view to be compiled
	 * @return ViewCompiler
	 */
	public function addView(View $view): ViewCompiler
	{
		array_push($this->views, $view);
		return $this;
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
		$filePath = $view->getFullFilePath();
		if (!file_exists($filePath)) {
			throw new CompileException($filePath);
		}

		// Compile the view file
		$filePath = $view->getRelativeFilePath();
		$parameters = array_merge($this->defaultParameters, $view->getParameters());
		$loader = new FilesystemLoader(RESOURCES_DIR . '/views');
		$twig = new Environment($loader, []);
		$output = $twig->render($filePath, $parameters);

		// Return the parsed view
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

	/**
	 * Clears the views in the view compiler.
	 *
	 * @return ViewCompiler
	 */
	public function clearViews(): ViewCompiler
	{
		$this->views = [];
		return $this;
	}

	/**
	 * Set a default parameter to be passed into every compiled view.
	 *
	 * @param string $name
	 * @param string $value
	 * @return ViewCompiler
	 */
	public function setDefaultParameter(string $name, string $value): ViewCompiler
	{
		$this->defaultParameters[$name] = $value;
		return $this;
	}

	/**
	 * Get a default parameter.
	 *
	 * @param string $name
	 * @return string
	 */
	public function getDefaultParameter(string $name): string
	{
		return $this->defaultParameters[$name];
	}

	/**
	 * Unset a default parameter.
	 *
	 * @param string $name
	 * @return ViewCompiler
	 */
	public function removeDefaultParameter(string $name): ViewCompiler
	{
		unset($this->defaultParameters[$name]);
		return $this;
	}

	/**
	 * Checks if a default parameter has been set.
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function hasDefaultParameter(string $name): bool
	{
		return isset($this->defaultParameters[$name]);
	}
}

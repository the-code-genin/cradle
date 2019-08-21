<?php
namespace Cradle\Components;

use Cradle\Managers\{FileManager, CookieManager, RequestManager, SessionManager};
use Cradle\Routing\Dispatcher;
use const App\Config\ROUTES;

/**
 * The base class for all controllers in the system.
 * All valid controllers must either extend this class or extend one of its subclasses.
 */
abstract class Controller
{
	/** @var FileManager $files A file manager instance */
	protected $files;

	/** @var CookieManager $cookies A cookie manager instance */
	protected $cookies;

	/** @var SessionManager $session A session manager instance */
	protected $session;

	/** @var RequestManager $request A request manager instance */
	protected $request;

	/** @var ViewCompiler $viewCompiler Holds the ViewCompiler object for the controller */
	protected $viewCompiler;

	/** @var bool $outputOverridden Confirms if there is alternate output to be sent back to the client */
	protected $outputOverridden = false;

	/** @var string $output Alternate output to be sent back to the client */
	protected $output = '';

	/** @var int $startTime The time this cradle app was fired up */
	protected $startTime;

	/**
	 * @param int $startTime The time this cradle app was fired up
	 */
	public function __construct(int $startTime)
	{
		$this->viewCompiler = new ViewCompiler();
		$this->files = new FileManager();
		$this->cookies = new CookieManager();
		$this->session = new SessionManager();
		$this->request = new RequestManager();
		$this->startTime = $startTime;
	}

	/**
	 * Loads a view file into the ViewCompiler.
	 * 
	 * @param string $filePath The file path of the view file to be loaded relative to the views directory
	 * @param array $param An array of parameters to be passed into the view file
	 * 
	 * @return null
	 */
	protected function loadView(string $filePath, array $param = []): void
	{
		$view = new View($filePath, $param);
		$this->viewCompiler->addView($view);
	}

	/**
	 * Gets the output to be sent as response to the client.
	 * 
	 * @return mixed Output of the controller operation
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
	 * 
	 * @param mixed $output Alternate output to be send back to the client
	 * 
	 * @return bool Returns true on success and false on failure
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
	 * 
	 * @param string $path The file path of the file to be set as the output file
	 * 
	 * @return bool Returns true on success and false on failure
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
	 * 
	 * @param string $header The header to be set
	 * @param string $value The value of the header to be set
	 * 
	 * @return null
	 */
	protected function setHeader(string $header, string $value): void
	{
		header("$header: $value");
	}

	/**
	 * Sets the http response code
	 * 
	 * @param int $code The status code to be set
	 * 
	 * @return null
	 */
	protected function setStatusCode(int $code): void
	{
		http_response_code($code);
	}

	/**
	 * Gets how many seconds this cradle app has been running for.
	 * 
	 * @return int The amount of seconds this cradle app has been running for
	 */
	public function getExecutionTime(): int
	{
		return time() - $this->startTime;
	}

	/**
	 * Gets the maximum execution time for this cradle app.
	 * 
	 * @return int The max execution time for the cradle app
	 */
	public function getMaxExecutionTime(): int
	{
		return (int) @ini_get('max_execution_time');
	}

	/**
	 * Show the 404 error page.
	 * Never call this method from the 404 error controller, will cause recursion hell!
	 * 
	 * @return null
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
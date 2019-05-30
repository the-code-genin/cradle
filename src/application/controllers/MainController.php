<?php
namespace Cradle\Application\Controllers;

use Cradle\Framework\Components\BaseController;

class MainController extends BaseController
{
	/**
	 * The index page.
	 */
	public function index(): void
	{
		$this->loadView('home');
	}

	/**
	 * The site maintenance page.
	 */
	public function maintenance(): void
	{
		$this->loadView('framework/maintenance');
	}

	/**
	 * The 404 error page.
	 */
	public function error404(): void
	{
		http_response_code(404);
		$this->loadView('framework/error404');
	}
}

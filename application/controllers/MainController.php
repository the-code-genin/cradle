<?php
namespace Cradle\Application\Controllers;

class MainController extends \Cradle\Core\Controller
{
	/**
	 * The index page
	 */
	public function index(): void
	{
		$this->loadView('home');
	}

	/**
	 * The 404 error page
	 */
	public function error404(): void
	{
		http_response_code(404);
		$this->loadView('error404');
	}
}
